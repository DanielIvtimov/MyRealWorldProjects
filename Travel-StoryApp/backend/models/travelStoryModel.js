import { travelStoryMongoSchema } from "../schemas/travel_story_schemas.js";
import travelStoryValidation from "../services/validations/travel_story_validations.js";


export class TravelStory{
    async addTravelStory(data){
        const { error, value } = travelStoryValidation.validate(data);
        if(error){
            throw new Error(error.details[0].message);
        }
        const parsedVisitedDate = new Date(value.visitedDate);
        const travelStory = new travelStoryMongoSchema({
            title: value.title,
            story: value.story,
            visitedLocation: value.visitedLocation,
            imageUrl: value.imageUrl,
            userId: value.userId,
            visitedDate: parsedVisitedDate,
        })
        const savedStory = await travelStory.save();
        return savedStory;
    }

    async getAllStories(userId){
        if(!userId){
            throw new Error("User ID is required");
        }
        const stories = await travelStoryMongoSchema.find({ userId }).sort({ isFavourite: -1 });
        return stories;
    }

}