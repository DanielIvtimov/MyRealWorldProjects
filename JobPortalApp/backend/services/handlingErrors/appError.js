class AppError extends Error {
  constructor(message, statusCode) {
    super(message);
    this.statusCode = statusCode;
    this.isOperational = true;
    Error.captureStackTrace(this, this.constructor);
  }
}

export class ValidationError extends AppError {
    constructor(message){
        super(message || "Validation Error:", 400);
    }
}

export class ConflictError extends AppError {
    constructor(message){
        super(message || "Conflict", 409);
    }
}

export class NotFoundError extends AppError {
  constructor(message) {
    super(message || "Not Found", 404);
  }
}

export class UnauthorizedError extends AppError {
  constructor(message) {
    super(message || "Unauthorized", 401);
  }
}

export class ForbiddenError extends AppError {
  constructor(message) {
    super(message || "Forbidden", 403);
  }
}

export class InternalServerError extends AppError {
  constructor(message) {
    super(message || "Internal Server Error", 500);
  }
} 