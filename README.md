# Book API

## Setup Instructions

1. Clone the repository.
2. Navigate to the project directory.
3. Run `composer install` to install dependencies.
4. Set up your environment: `cp .env.example .env`.
5. Generate an application key: `php artisan key:generate`.
6. Run migrations: `php artisan migrate`.
7. Seed the database: `php artisan db:seed --class=BookSeeder`.
8. Start the server: `php artisan serve`.

## API Endpoints

## 1. User Registration

    Endpoint: POST /api/register
    Request Body:

```json
{
"name": "John Doe",
"email": "john@example.com",
"password": "password123"
}

Example Response (Success, 201):
json

{
"message": "User registered successfully"
}
Example Response (Failure, 500):
json

{
"error": "Registration failed",
"message": "The email has already been taken."
}

## 2. User Login

Endpoint: POST /api/login
Request Body:
json

{
"email": "john@example.com",
"password": "password123"
}
Example Response (Success, 200):
json

{
"token": "your_api_token_here"
}
Example Response (Failure, 422):
json

{
"error": "Invalid credentials",
"message": "The provided credentials are incorrect."
}

## 3. User Logout

Endpoint: POST /api/logout
Headers: Authorization: Bearer {your_api_token_here}
Example Response (Success, 200):
json

{
"message": "Logged out successfully"
}
Example Response (Failure, 500):
json

{
"error": "Logout failed",
"message": "Error message if any."
}

##  4. Get All Books with Pagination and Search

Endpoint: GET /api/books
Optional Query Parameters:
page: Page number for pagination.
search: Keyword for searching by title, author, or genre.
Example Request: GET /api/books?search=Science&page=1
Example Response (Success, 200):
json

{
"data": [
{
"id": 1,
"title": "Science for Everyone",
"author": "John Doe",
"published_date": "2023-10-01",
"genre": "Science",
"created_at": "2023-10-20T12:34:56Z",
"updated_at": "2023-10-20T12:34:56Z"
},
// More book entries
],
"links": {
"first": "http://api.example.com/api/books?page=1",
"last": "http://api.example.com/api/books?page=10",
"prev": null,
"next": "http://api.example.com/api/books?page=2"
},
"meta": {
"current_page": 1,
"from": 1,
"last_page": 10,
"path": "http://api.example.com/api/books",
"per_page": 10,
"to": 10,
"total": 100
}
}

## 5. Create a Book

Endpoint: POST /api/books
Headers: Authorization: Bearer {your_api_token_here}
Request Body:
json

{
"title": "New Book Title",
"author": "Author Name",
"published_date": "2024-01-01",
"genre": "Fiction"
}
Example Response (Success, 201):
json

{
"message": "Book created successfully",
"book": {
"id": 101,
"title": "New Book Title",
"author": "Author Name",
"published_date": "2024-01-01",
"genre": "Fiction",
"created_at": "2024-01-01T12:00:00Z",
"updated_at": "2024-01-01T12:00:00Z"
}
}

## 6. Show a Specific Book

Endpoint: GET /api/books/{id}
Example Response (Success, 200):
json

{
"id": 1,
"title": "Science for Everyone",
"author": "John Doe",
"published_date": "2023-10-01",
"genre": "Science",
"created_at": "2023-10-20T12:34:56Z",
"updated_at": "2023-10-20T12:34:56Z"
}
Example Response (Not Found, 404):
json

{
"error": "Book not found"
}

## 7. Update a Book

Endpoint: PUT /api/books/{id}
Headers: Authorization: Bearer {your_api_token_here}
Request Body:
json

{
"title": "Updated Book Title",
"author": "Updated Author Name"
}
Example Response (Success, 200):
json

{
"message": "Book updated successfully",
"book": {
"id": 1,
"title": "Updated Book Title",
"author": "Updated Author Name",
"published_date": "2023-10-01",
"genre": "Science",
"created_at": "2023-10-20T12:34:56Z",
"updated_at": "2023-10-20T15:00:00Z"
}
}

## 8. Delete a Book (Soft Delete)

Endpoint: DELETE /api/books/{id}
Headers: Authorization: Bearer {your_api_token_here}
Example Response (Success, 200):
json

{
"message": "Book deleted successfully"
}
Example Response (Not Found, 404):
json

{
"error": "Book not found"
}

## 9. Search Books by Genre or Author

Endpoint: GET /api/books/search?search={query}
Example Request: GET /api/books/search?search=Fiction
Example Response (Success, 200):
json

[
{
"id": 1,
"title": "Mystery Novel",
"author": "Jane Doe",
"published_date": "2023-05-10",
"genre": "Fiction",
"created_at": "2023-05-15T12:00:00Z",
"updated_at": "2023-05-15T12:00:00Z"
},
]

## Authentication

Use Laravel Sanctum for API authentication. Ensure to include the token in the request headers for protected routes.
```
