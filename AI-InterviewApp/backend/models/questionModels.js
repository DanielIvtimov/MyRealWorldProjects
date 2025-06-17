import { sessionMongoSchema } from "../schemas/session_schema.js";
import { questionMongoSchema } from "../schemas/question_schema.js";

export class Question{
    async addQuestionToSession({sessionId, questions}){
        if(!sessionId || !questions || !Array.isArray(questions)){
            throw new Error("Invalid input data");
        }
        const session = await sessionMongoSchema.findById(sessionId);
        if(!session){
            throw new Error("Session not found");
        }
        const createdQuestions = await questionMongoSchema.insertMany(
            questions.map((q) => ({
                session: sessionId,
                question: q.question,
                answer: q.answer,
            }))
        );
        session.questions.push(...createdQuestions.map((q) => q._id));
        await session.save(createdQuestions);
        return createdQuestions;
    }

    async togglePinQuestion(id){
        const question = await questionMongoSchema.findById(id);
        if(!question){
            throw new Error("Question not found");
        }
        question.isPinned = !question.isPinned;
        await question.save();
        return question;
    }

    async updateQuestionNote(id, note){
        const question = await questionMongoSchema.findById(id);
        if(!question){
            throw new Error("Question not found");
        }
        question.note = note || "",
        await question.save();
        return question;
    }
}

