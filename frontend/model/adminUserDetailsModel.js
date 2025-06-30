export default class User {
    constructor(userData) {
        this.id = userData.id;
        this.username = userData.username;
        this.firstName = userData.firstName;
        this.lastName = userData.lastName;
        this.email = userData.email;
        this.phoneNumber = userData.phoneNumber;
        this.role = userData.role;
        this.description = userData.description || 'No description provided.'; //default value
        this.country = userData.country;
        this.county = userData.county;
        this.telegramHandle = userData.telegramHandle;
        this.profilePicture = userData.profilePicture;
        this.bannerPicture = userData.bannerPicture;
    }

}