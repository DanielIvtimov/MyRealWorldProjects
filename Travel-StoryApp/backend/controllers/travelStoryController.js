import { TravelStory } from "../models/travelStoryModel.js";


export class TravelStoryController {
    constructor(){
        this.travelStoryModel = new TravelStory();
    }

    async addTravelStory(request, response){
        try{
            const data = {...request.body, userId: request.user.id}
           const newStory = await this.travelStoryModel.addTravelStory((data));
           return response.status(201).json({ story: newStory, message: "Story added successfully"});
        }catch(error){
            return response.status(400).json({error: true, message: error.message});
        }
    }

    async getAllStories(request, response){
        try{
           const userId = request.user.id;
           const stories = await this.travelStoryModel.getAllStories(userId);
           return response.status(200).json({ stories }); 
        }catch(error){
            return response.status(500).json({error: true, message: error.message})   
        }
    }
}