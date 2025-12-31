# Books

---

## 🚀 Швидкий старт

### 1. Клонувати репозиторій
```bash
git clone https://github.com/serbynskyi/books.git
```

### 2. Створити файл змінних середовища
```bash
cp .env.example .env
```

### 3. Запустити проєкт
```bash
docker compose up
```

### 4. Виконати міграції бази даних
```bash
docker compose exec books php artisan migrate --seed

```

---

## Приклади запитів

### GET /api/books
```bash
curl --location 'http://localhost/api/books'
```

### GET /api/books/{book}
```bash
curl --location 'http://localhost/api/books/4'
```

### POST /api/books
```bash
curl --location 'http://localhost/api/books' \
--header 'Content-Type: application/json' \
--data '{
"authors": ["author3", "author4"],
"title": "title8",
"genres": ["genre1"],
"description": "description8",
"edition": 1,
"publisher": "publisher1",
"published_at": "2025-12-15",
"format": "format8",
"pages": 3,
"country": "country8",
"isbn": "isbn9"
}'
```

### PUT /api/books/{book}
```bash
curl --location --request PUT 'http://localhost/api/books/6' \
--header 'Content-Type: application/json' \
--data '{
"authors": [{"author": "author3"}],
"title": "title9",
"description": "description9",
"edition": 2,
"publisher": "publisher1"
}'
```

### DEL /api/books/{book}
```bash
curl --location --request DELETE 'http://localhost/api/books/2'
```

### POST /api/import
```bash
curl --location 'http://localhost/api/import' \
--form 'file=@"/home/user/Downloads/f0c063d9-2f9a-479a-809f-3c504494c5d6.csv"'
```
