# 🧠 InterviewPrepAI – Gemini Integration

This project uses the Google Gemini (GenAI) API to automatically generate interview questions and conceptual explanations. All you need to do is provide a role, experience level, and topics – and it returns ready-made Q&A as if you're in a real interview.
---

## ⚙️ Installation and Configuration

### 1. Installing the Required Package

The Gemini API is available via the official @google/genai NPM package:

### 2. Setting Up Environment Variables

Create a .env file in the root directory and add your API key:

### 3. Initialization in Code

In your Node.js project, load the environment variables and initialize the Gemini AI client library:

## 🚀 Using the Google Gemini API

- Communication with the model is done via the `generateContent` method..
- Prompts are defined as template literals with clear instructions for the AI model.
- An example for generating interview questions, answers, and concept explanations is implemented in `/services/customHelper`.

---

## 📚 Sources and Documentation

To implement this project and learn how to use the Google Gemini API, I used the following resources:

- [Official Google Gemini API Documentation](https://ai.google.dev/gemini-api/docs/quickstart) – For basic configuration and examples.
- [Google AI Studio and Gemini API tutorials](https://developers.google.com/learn/pathways/solution-ai-gemini-101) – for prototyping and Node.js integration.
- YouTube video tutorials:
  - [Google Gemini AI API - JavaScript & Node JS Tutorial](https://www.youtube.com/watch?v=l6AGRZ-RK1s)  – step-by-step integration.
  - [Master the Gemini API: A Node.js tutorial with real examples](https://www.youtube.com/watch?v=Z8F6FvMrN4o) – Practical examples.
  - [Integrating Gemini API in Node.js: A Step-by-Step Tutorial!](https://www.youtube.com/watch?v=mMalSD_y-ac) – API key generation and basic setup.
- Example projects on GitHub:
  - [samson-shukla/google-gemini-ai](https://github.com/samson-shukla/google-gemini-ai) – Node.js server for Google Gemini API.

---

## 📝 Notes

- It is important to call  `dotenv.config()` before using environment variables.
- Prompts are key to getting high-quality and relevant responses from the AI model.
- For testing, I use Postman.
- The key to successful integration is properly setting the API key and having the correct prompt structure.

---

## 📂 Project Structure

- `/config` – database configuration
- `/middleware` – authentication middleware
- `/routes` – API routes
- `/services/customHelper` – logic for communication with GoogleGenAI
- `/utils` – defined prompts and helper functions

---