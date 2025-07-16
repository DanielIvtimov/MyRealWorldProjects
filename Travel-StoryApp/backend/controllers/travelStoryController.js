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

    async editTravelStory(request, response){
        const { id } = request.params;
        const userId = request.user.id;
        try{
            const updatedStory = await this.travelStoryModel.editTravelStory(id, userId, request.body);
            return response.status(200).json({
                error: false,
                story: updatedStory,
                message: "Updated Successfully",
            });
        }catch(error){
            return response.status(400).json({error: true, message: error.message });
        }
    }

    async deleteTravelStory(reqeust, response){
        const { id } = reqeust.params;
        const userId = reqeust.user.id;
        try{
           await this.travelStoryModel.deleteTravelStory(id, userId);
           return response.status(200).json({error: false, message: "Travel story deleted successfully"});
        }catch(error){
          return response.status(400).json({error: true, message: error.message});   
        }
    }
}