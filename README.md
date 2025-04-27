# 📚 API Documentation Request: Detailed Endpoint Specs

---

## Public Endpoints (No Authentication Required)

---

### `POST /api/login`
- **Description**:  
  Authenticates a user and issues a Sanctum token for API requests.

- **Request Body**:
  ```json
  {
    "email": "user@example.com",
    "password": "your_password"
  }
  ```
  
- **Responses**:
  - **200 OK**  
    ```json
    {
      "token": "your_sanctum_token",
      "user": {
        "id": 1,
        "name": "User Name",
        "email": "user@example.com"
      }
    }
    ```
  - **422 Validation Error** (Missing fields)
  - **401 Unauthorized** (Wrong credentials)

- **Notes**:
  - Please specify the token lifetime or expiration, if any.
  - Clarify if token needs to be refreshed.

---

### `GET /api/posts`
- **Description**:  
  Fetches a paginated list of all published posts.

- **Query Parameters** (optional):
  - `page`: int (default `1`)
  - `per_page`: int (default, e.g., `10`)

- **Responses**:
  - **200 OK**  
    ```json
    {
      "data": [
        {
          "id": 1,
          "title": "Post Title",
          "slug": "post-title",
          "excerpt": "Small summary",
          "published_at": "2025-04-25T12:34:56Z"
        },
        ...
      ],
      "meta": {
        "current_page": 1,
        "last_page": 10,
        "total": 100
      }
    }
    ```

- **Notes**:
  - Specify if the posts are sorted (e.g., by latest first).
  - Are drafts included or only published posts?

---

### `GET /api/posts/{slug}`
- **Description**:  
  Retrieves a full detailed view of a single post by slug.

- **Path Parameters**:
  - `slug`: string (e.g., `"how-to-code-in-python"`)

- **Responses**:
  - **200 OK**  
    ```json
    {
      "id": 1,
      "title": "Post Title",
      "slug": "post-title",
      "body": "Full post content",
      "published_at": "2025-04-25T12:34:56Z",
      "author": {
        "id": 1,
        "name": "Author Name"
      },
      "categories": [
        {
          "id": 2,
          "name": "Tech",
          "slug": "tech"
        }
      ]
    }
    ```
  - **404 Not Found** (Post does not exist)

- **Notes**:
  - What happens if the slug points to a draft post? Should it 404 publicly?

---

### `GET /api/posts/get/{slug}`
- **Description**:  
  Duplicate purpose? Seems similar to `/posts/{slug}`.
  
- **Clarification Needed**:
  - How is this different from `/posts/{slug}`?
    - Does it include drafts?
    - Admin-level view?
    - Less/more fields?

- **Request**:  
  Document the difference or **deprecate** if redundant.

---

### `GET /api/posts/related/{slug}`
- **Description**:  
  Fetches a list of related posts based on a given post slug.

- **Path Parameters**:
  - `slug`: string (the reference post)

- **Responses**:
  - **200 OK**  
    ```json
    [
      {
        "id": 2,
        "title": "Another Related Post",
        "slug": "another-related-post",
        "excerpt": "Short description"
      },
      ...
    ]
    ```

- **Notes**:
  - How are "related" posts determined? (Same category? Keywords? Author?)
  - Limit number of related posts returned (e.g., top 5)?

---

### `GET /api/category/{slug}/posts`
- **Description**:  
  Fetches all posts under a given category.

- **Path Parameters**:
  - `slug`: string (e.g., `"technology"`)

- **Responses**:
  - **200 OK**  
    ```json
    {
      "category": {
        "id": 2,
        "name": "Technology",
        "slug": "technology"
      },
      "posts": [
        {
          "id": 1,
          "title": "Tech Post 1",
          "slug": "tech-post-1"
        },
        ...
      ]
    }
    ```

- **Notes**:
  - Pagination supported here too?
  - Are posts filtered by status (only published)?

---

# 🛡️ Protected Endpoints (Require Authenticated Sanctum Token)

Middleware: `auth:sanctum`

Use token from the logged in user in the Header.

Authorization: `Bearer {token}`

Input the token without the curly brackets
---

### `GET /api/user`
- **Description**:  
  Returns the currently authenticated user's details.

- **Response**:
  ```json
  {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com",
    "created_at": "2025-01-01T00:00:00Z"
  }
  ```

- **Notes**:
  - Useful for frontend to validate session state.

---

### `POST /api/logout`
- **Description**:  
  Logs out the user and revokes their token.

- **Response**:
  - **200 OK**  
    ```json
    {
      "message": "Successfully logged out"
    }
    ```

- **Notes**:
  - Will all tokens for the user be revoked or just the current one?

---

### `POST /api/posts`
- **Description**:  
  Creates a new post.

- **Request Body**:
  ```json
  {
    "title": "New Post Title",
    "slug": "new-post-title",
    "body": "Full post content",
    "category_id": [1, 2],
    "status": "published"  // or "draft"
  }
  ```

- **Responses**:
  - **201 Created**  
    ```json
    {
      "message": "Post created successfully",
      "post": {...}
    }
    ```
  - **422 Validation Error**

- **Notes**:
  - Is slug auto-generated if omitted?
  - Are multiple categories allowed?

---

### `PUT /api/posts/{slug}`
- **Description**:  
  Updates an existing post identified by its slug.

- **Request Body**: (Same fields as `POST /posts`, but all fields optional)

- **Responses**:
  - **200 OK**  
    ```json
    {
      "message": "Post updated successfully",
      "post": {...}
    }
    ```

- **Notes**:
  - Can slug be updated?
  - What happens if slug changes — will old slug redirect?

---

### `DELETE /api/posts/{slug}`
- **Description**:  
  Deletes a post by slug.

- **Response**:
  - **200 OK**  
    ```json
    {
      "message": "Post deleted successfully"
    }
    ```

- **Notes**:
  - Hard delete or soft delete (trashed)?  
  - If soft delete, can it be restored later?

---

### `POST /api/categories`
- **Description**:  
  Creates a new category.

- **Request Body**:
  ```json
  {
    "name": "New Category",
    "slug": "new-category"
  }
  ```

- **Response**:
  - **201 Created**  
    ```json
    {
      "message": "Category created successfully",
      "category": {...}
    }
    ```

- **Notes**:
  - Is slug auto-generated from name if not supplied?
  - Should category names be unique?

---

### `PUT /api/categories/{slug}`
- **Description**:  
  Updates an existing category.

- **Request Body**:
  ```json
  {
    "name": "Updated Category Name",
    "slug": "updated-category-slug"
  }
  ```

- **Response**:
  - **200 OK**  
    ```json
    {
      "message": "Category updated successfully",
      "category": {...}
    }
    ```

---

### `DELETE /api/categories/{slug}`
- **Description**:  
  Deletes a category by slug.

- **Response**:
  - **200 OK**  
    ```json
    {
      "message": "Category deleted successfully"
    }
    ```


