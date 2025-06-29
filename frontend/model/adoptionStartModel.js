export class User {
    constructor(id, profilePicture, firstName, lastName, username, email, phoneNumber, description, county, country, telegramHandle, bannerPicture) {
        this.id = id;
        this.profilePicture = profilePicture;
        this.firstName = firstName;
        this.lastName = lastName;
        this.username = username;
        this.email = email;
        this.phoneNumber = phoneNumber;
        this.description = description;
        this.county = county;
        this.country = country;
        this.telegramHandle = telegramHandle;
        this.bannerPicture = bannerPicture;
    }
}