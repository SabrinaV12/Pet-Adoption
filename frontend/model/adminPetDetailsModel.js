export default class Pet {
    constructor(petData) {
        this.id = petData.id;
        this.name = petData.name;
        this.breed = petData.breed;
        this.gender = petData.gender;
        this.age = petData.age;
        this.color = petData.color;
        this.weight = petData.weight;
        this.height = petData.height;
        this.animal_type = petData.animal_type;
        this.image_path = petData.image_path ? `http://localhost/Pet_Adoption/public/pet-uploads/${petData.image_path}` : 'http://localhost/Pet_Adoption/images/placeholder.png'; //TEMPORARY!!!!!!
        this.size = petData.size;

        this.vaccinated = Boolean(petData.vaccinated);
        this.house_trained = Boolean(petData.house_trained);
        this.neutered = Boolean(petData.neutered);
        this.microchipped = Boolean(petData.microchipped);
        this.good_with_children = Boolean(petData.good_with_children);
        this.shots_up_to_date = Boolean(petData.shots_up_to_date);

        this.restrictions = petData.restrictions || 'No restrictions listed.';
        this.recommended = petData.recommended || 'No specific recommendations.';
        this.description = petData.description || 'No description provided.';

        this.adopted = Boolean(petData.adopted);
        this.adoption_date = petData.adoption_date;
        this.user_id = petData.user_id;
        this.created_at = petData.created_at;
    }
}