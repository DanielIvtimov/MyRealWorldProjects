import usersValidation from "./users_validation.js";

const loginValidation = usersValidation.fork(["fullName", "createdOn"], (schema) => schema.forbidden());

export default loginValidation;