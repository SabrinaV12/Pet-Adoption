<?php

require_once __DIR__ . '/../repositories/ApplicationRepository.php';
require_once __DIR__ . '/../models/applications.php';

class AdoptionService
{
    private $applicationRepository;

    public function __construct()
    {
        $this->applicationRepository = new ApplicationRepository();
    }

    public function submitApplication(array $data, int $applicantId): array
    {
        $errors = $this->validate($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $application = new Applications(
            null,
            $data['pet_id'],
            $applicantId,
            $data['address']['line1'],
            $data['address']['line2'],
            $data['address']['city'],
            $data['address']['postcode'],
            $data['home']['has_garden'],
            $data['address']['phone'],
            $data['home']['situation'],
            $data['home']['setting'],
            $data['home']['level'],
            $data['roommates']['num_children'],
            $data['roommates']['num_adults'],
            $data['roommates']['youngest_age'],
            $data['roommates']['visiting_children'],
            $data['roommates']['visiting_ages'],
            $data['roommates']['has_flatmates'],
            $data['pets']['allergies'],
            $data['pets']['other_animals_info'],
            $data['pets']['has_animals'],
            $data['pets']['neutered'],
            $data['pets']['vaccinated'],
            $data['pets']['experience'],
            null,
            'Pending'
        );

        $success = $this->applicationRepository->save($application);

        if (!$success) {
            return ['success' => false, 'errors' => ['database' => 'A server error occurred while saving the application.']];
        }

        return ['success' => true];
    }

    private function validate(array $data): array
    {

        $errors = [];

        if (empty($data['address']['line1'])) $errors['address_line1'] = 'Address Line 1 is required.';
        if (empty($data['address']['postcode'])) $errors['postcode'] = 'Postcode is required.';
        if (empty($data['address']['city'])) $errors['city'] = 'City is required.';
        if (empty($data['address']['phone'])) $errors['phone'] = 'Phone Number is required.';

        if (empty($data['home']['has_garden'])) $errors['has_garden'] = 'Garden information is required.';
        if (empty($data['home']['situation'])) $errors['living_situation'] = 'Living situation description is required.';
        if (empty($data['home']['setting'])) $errors['household_setting'] = 'Household setting description is required.';
        if (empty($data['home']['level'])) $errors['activity_level'] = 'Household activity level is required.';

        if (!isset($data['roommates']['num_adults']) || (int)$data['roommates']['num_adults'] < 1) {
            $errors['num_adults'] = 'Number of adults must be at least 1.';
        }
        if (!isset($data['roommates']['num_children']) || (int)$data['roommates']['num_children'] < 0) {
            $errors['num_children'] = 'Number of children must be 0 or more.';
        }
        if (!isset($data['roommates']['visiting_children'])) {
            $errors['visiting_children'] = 'Information about visiting children is required.';
        }
        if (!isset($data['roommates']['has_flatmates'])) {
            $errors['has_flatmates'] = 'Information about flatmates is required.';
        }

        if ((int)($data['roommates']['num_children'] ?? 0) > 0) {
            if (empty($data['roommates']['youngest_age'])) {
                $errors['youngest_age'] = 'Age of the youngest child is required when children are present.';
            }
        }
        if (($data['roommates']['visiting_children'] ?? '') === 'yes') {
            if (empty($data['roommates']['visiting_ages'])) {
                $errors['visiting_ages'] = 'Age of visiting children is required if they visit.';
            }
        }

        if (empty($data['pets']['allergies'])) $errors['allergies'] = 'Allergy information is required.';
        if (empty($data['pets']['experience'])) $errors['experience'] = 'Experience description is required.';
        if (!isset($data['pets']['has_animals'])) $errors['has_animals'] = 'Information about other animals is required.';

        if (($data['pets']['has_animals'] ?? '') === 'yes') {
            if (empty($data['pets']['other_animals_info'])) $errors['other_animals_info'] = 'Details about other animals are required.';
            if (empty($data['pets']['neutered'])) $errors['neutered'] = 'Neutered status of other animals is required.';
            if (empty($data['pets']['vaccinated'])) $errors['vaccinated'] = 'Vaccination status of other animals is required.';
        }

        return $errors;
    }
}
