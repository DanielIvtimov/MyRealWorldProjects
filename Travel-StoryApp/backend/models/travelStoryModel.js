import { travelStoryMongoSchema } from "../schemas/travel_story_schemas.js";
import travelStoryValidation from "../services/validations/travel_story_validations.js";
import path from "path";
import fs from "fs";

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

    async editTravelStory(id, userId, data){
        const dataWithUserId = { ...data, userId };
        const { error, value } = travelStoryValidation.validate(dataWithUserId);
        if(error){
            throw new Error(error.details[0].message);
        } 
        const parsedVisitedDate = new Date(value.visitedDate);
        const travelStory = await travelStoryMongoSchema.findOne({ _id: id, userId });
        if (!travelStory) {
            throw new Error("Travel story not found");
        }
        const placeholderImgUrl = `http://localhost:8000/assets/placeholder.png`;
        travelStory.title = value.title;
        travelStory.story = value.story;
        travelStory.visitedLocation = value.visitedLocation;
        travelStory.imageUrl = value.imageUrl || placeholderImgUrl;
        travelStory.visitedDate = parsedVisitedDate;

        const updatedStory = await travelStory.save();
        return updatedStory;
    }

    async deleteTravelStory(id, userId){
        const travelStory = await travelStoryMongoSchema.findOne({ _id: id, userId });
        if(!travelStory){
            throw new Error("Travel not found");
        }
        await travelStoryMongoSchema.deleteOne({ _id: id, userId });
        const imageUrl = travelStory.imageUrl;
        const filename = path.basename(imageUrl);
        const filePath = path.join(path.resolve(), "uploads", filename);
        fs.unlink(filePath, (error) => {
            if(error && error.code !== "ENOENT"){
                console.log("Failed to delete image file:", error);
            } else {
                console.log("Skip file deletion: Image is an external URL.");
            }
        });
        return true;
    }

    async updateIsFavourite(id, userId, isFavourite){
        const travelStory = await travelStoryMongoSchema.findOne({_id: id, userId});
        if(!travelStory){
            throw new Error("Travel story not found");
        }
        travelStory.isFavourite = isFavourite;
        await travelStory.save();
        return travelStory;
    }

    async searchTravelStory(userId, query){
        if(!query){
            throw new Error("Query is required");
        }
        const searchResults = await travelStoryMongoSchema.find({
            userId: userId,
            $or: [
            { title: { $regex: query, $options: "i" } },
            { story: { $regex: query, $options: "i" } },
            { visitedLocation: { $regex: query, $options: "i" } },
            ],
        }).sort({ isFavourite: -1 });
        return searchResults;
    }

    async travelStoryFilter(userId, startDate, endDate){
        const start = new Date(parseInt(startDate));
        const end = new Date(parseInt(endDate));
        const filteredStories = await travelStoryMongoSchema.find({
            userId,
            visitedDate: { $gte: start, $lte: end },
        }).sort({ isFavourite: -1 });
        return filteredStories;
    }
}