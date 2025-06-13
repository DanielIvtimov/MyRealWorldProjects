import { sessionMongoSchema } from "../schemas/session_schema.js";
import { questionMongoSchema } from "../schemas/question_schema.js";

export class Session{
    async createSession({userId, role, experience, topicsToFocus, description, questions}){
        const session = await sessionMongoSchema.create({
            user: userId,
            role,
            experience,
            topicsToFocus,
            description,
        });
        const questionDocs = await Promise.all(
            questions.map(async (q) => {
                const question = await questionMongoSchema.create({
                    session: session._id,
                    question: q.question,
                    answer: q.answer,
                });
                return question._id;
            })
        );
        session.questions = questionDocs;
        await session.save();
        return session;
    }

    async getMySessions(userId){
        const sessions = await sessionMongoSchema
            .find({user: userId})
            .sort({ createdAt: -1})
            .populate("questions");
        return sessions;
    }

    async getSessionById(sessionId){
        const session = await sessionMongoSchema.findById(sessionId)
            .populate({
                path: "questions",
                options: { sort: { isPinned: -1, createdAt: -1} },
            })
            .exec();
        return session;
    }

    async deleteSession(sessionId, userId){
        const session = await sessionMongoSchema.findById(sessionId);
        if(!session){
            throw new Error("Session not found");
        }
        if(session.user.toString() !== userId.toString()){
            throw new Error("Not authorized to delete this session");
        }
        await questionMongoSchema.deleteMany({session: session._id});
        await session.deleteOne();
        return {message: "Session deleted successfully"};
    }
}