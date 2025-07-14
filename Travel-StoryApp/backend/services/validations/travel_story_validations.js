import Joi from "joi";

const travelStoryValidation = Joi.object({
    title: Joi.string().min(1).required().messages({
        "string.base": "Title must be a string",
        "string.empty": "Title is required",
        "any.required": "Title is required",
    }),
    story: Joi.string().min(1).required().messages({
        "string.base": "Story must be a string",
        "string.empty": "Story is required",
        "any.required": "Story is required",
    }),
    visitedLocation: Joi.array().items(Joi.string()).min(1).required().messages({
        "array.base": "Visited Location must be an array of strings",
        "array.min": "At least one visited location is required",
        "any.required": "Visited Location is required",
    }),
    imageUrl: Joi.string().uri().required().messages({
        "string.base": "Image URL must be a string",
        "string.uri": "Image URL must be a valid URI",
        "any.required": "Image URL is required",
    }),
    visitedDate: Joi.number().required().messages({
    "number.base": "Visited Date must be a number (timestamp in ms)",
    "any.required": "Visited Date is required",
    }),
    userId: Joi.string().hex().length(24).required().messages({
        "string.base": "User ID must be a string",
        "string.length": "User ID must be 24 characters long",
        "any.required": "User ID is required",
    }),
});

export default travelStoryValidation;