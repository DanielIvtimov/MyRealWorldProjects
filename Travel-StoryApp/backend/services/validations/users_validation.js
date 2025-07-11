import Joi from "joi";

const usersValidation = Joi.object({
    fullName: Joi.string().min(2).max(50).required().messages({
        "string.base": "Full name must be a string",
        "string.empty": "Full name is required",
        "string.min": "Full name must be at least 2 characters",
        "string.max": "Full name must be at most 50 characters",
        "any.required": "Full name is required"
    }),
    email: Joi.string().email().required().messages({
        "string.base": "Email must be a string",
        "string.email": "Email must be a valid",
        "string.empty": "Email is required",
        "any.required": "Emails is requireed",
    }),
    password: Joi.string().min(6).required().messages({
        "string.base": "Password must be a string",
        "string.empty": "Password is required",
        "string.min": "Password must be at least 6 characters",
        "any.required": "Password is required",
    }),
    createdOn: Joi.date().default(() => new Date()),
})

export default usersValidation;


