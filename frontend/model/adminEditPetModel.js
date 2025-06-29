export class Vaccination {
    constructor(id, pet_id, age_in_weeks, vaccine_name) {
        this.id = id;
        this.petId = pet_id;
        this.ageInWeeks = age_in_weeks;
        this.vaccineName = vaccine_name;
    }
}

export class FeedingSchedule {
    constructor(id, pet_id, feed_date, food_type) {
        this.id = id;
        this.petId = pet_id;
        this.feedDate = feed_date;
        this.foodType = food_type;
    }
}

export class Pet {
    constructor(data) {
        this.id = data.pet.id;
        this.name = data.pet.name;
        this.gender = data.pet.gender;
        this.breed = data.pet.breed;
        this.age = data.pet.age;
        this.color = data.pet.color;
        this.weight = data.pet.weight;
        this.height = data.pet.height;
        this.animalType = data.pet.animal_type;
        this.imagePath = data.pet.image_path;
        this.size = data.pet.size;
        this.vaccinated = data.pet.vaccinated == 1;
        this.houseTrained = data.pet.house_trained == 1;
        this.neutered = data.pet.neutered == 1;
        this.microchipped = data.pet.microchipped == 1;
        this.goodWithChildren = data.pet.good_with_children == 1;
        this.shotsUpToDate = data.pet.shots_up_to_date == 1;
        this.restrictions = data.pet.restrictions;
        this.recommended = data.pet.recommended;
        this.adopted = data.pet.adopted == 1;
        this.adoptionDate = data.pet.adoption_date;
        this.description = data.pet.description;
        this.userId = data.pet.user_id;

        this.vaccinations = data.vaccinations.map(v => new Vaccination(v.id, v.pet_id, v.age_in_weeks, v.vaccine_name));

        this.feedingSchedules = data.feeding_schedules.map(f => new FeedingSchedule(f.id, f.pet_id, f.feed_date, f.food_type));
    }
}