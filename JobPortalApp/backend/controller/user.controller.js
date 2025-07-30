import { User } from "../model/user.model.js";


export class UserController {
    constructor(){
        this.userModel = new User();
    }

    async register(request, response){}
}