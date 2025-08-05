import Joi from "joi";

const userUpdateValidation = Joi.object({
    fullname: Joi.string().min(3).max(50).strict().optional().allow(""),
    email: Joi.string().email().optional().allow(""),
    phoneNumber: Joi.number().min(1000000).max(999999999999999).optional(),
    bio: Joi.string().optional().allow(""),
    skills: Joi.array().items(Joi.string()).optional(),
}).min(1);

export default userUpdateValidation;