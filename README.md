````md
## 🎯 Learning Objectives

By the end of this module, trainees will be able to:

1. Understand what **Bootstrap** is and how it works.
2. Use the **Bootstrap Grid System (12-column layout)** effectively.
3. Apply **Bootstrap classes** to build responsive layouts.
4. Include Bootstrap in a project using **CDN or local files**.
5. Build responsive UI components using Bootstrap utilities.

---

## 1. What is Bootstrap?

### 📌 Definition

**Bootstrap** is a popular front-end framework that helps developers build responsive and mobile-first websites quickly using pre-built CSS and JavaScript components.

---

### 🚀 Why Use Bootstrap?

- Speeds up development
- Provides ready-to-use components (buttons, forms, navbar, etc.)
- Built-in **responsive grid system**
- Consistent design across browsers

---

## 2. How to Add Bootstrap to Your Project

### ✅ Option 1: Using CDN (Recommended for beginners)

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <h1 class="text-center">Hello Bootstrap 👋</h1>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
````

---

### ✅ Option 2: Download Bootstrap

1. Go to: [https://getbootstrap.com](https://getbootstrap.com)
2. Download files
3. Include in your project:

```html
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="js/bootstrap.bundle.min.js"></script>
```

---

## 3. Bootstrap Grid System (12 Columns)

### 📌 What is the Grid System?

Bootstrap divides the page into **12 columns**. You can use these columns to create flexible and responsive layouts.

---

### 🧠 Key Concept

* The page is divided into **12 equal columns**
* You can combine columns (e.g., 6 + 6, 4 + 4 + 4)
* Must be wrapped inside `.container` → `.row` → `.col`

---

### 💡 Example

```html
<div class="container">
  <div class="row">
    <div class="col-6 bg-primary text-white">Column 1</div>
    <div class="col-6 bg-secondary text-white">Column 2</div>
  </div>
</div>
```

---

### 📱 Responsive Columns

```html
<div class="row">
  <div class="col-12 col-md-6 col-lg-4 bg-info">Responsive Column</div>
</div>
```

| Class    | Meaning                     |
| -------- | --------------------------- |
| col-12   | Full width on small screens |
| col-md-6 | Half width on tablets       |
| col-lg-4 | One-third on large screens  |

---

### 🧠 Exercise 1

* Create a layout with:

  * 3 equal columns on desktop
  * 1 column on mobile

---

## 4. Understanding Bootstrap Classes

### 📌 What are Classes?

Bootstrap uses predefined **CSS classes** to style elements.

---

### 💡 Examples

```html
<button class="btn btn-primary">Click Me</button>

<p class="text-danger">Error message</p>

<div class="p-3 m-2 bg-light">Box with padding & margin</div>
```

---

### 📊 Common Class Categories

| Category   | Example                            |
| ---------- | ---------------------------------- |
| Colors     | `text-primary`, `bg-dark`          |
| Spacing    | `p-3`, `m-2`                       |
| Flexbox    | `d-flex`, `justify-content-center` |
| Typography | `fw-bold`, `text-center`           |

---

### 🧠 Exercise 2

* Create a button using Bootstrap
* Add spacing and color utilities

---

## 5. Bootstrap Components

### 📌 What are Components?

Reusable UI elements like:

* Buttons
* Cards
* Navbar
* Modals
* Alerts

---

### 💡 Example: Card Component

```html
<div class="card" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">Product</h5>
    <p class="card-text">This is a simple card.</p>
    <a href="#" class="btn btn-primary">Buy Now</a>
  </div>
</div>
```

---

### 🧠 Exercise 3

* Create a product card using Bootstrap
* Add button and text styling

---

## 6. Responsive Design with Bootstrap

Bootstrap uses **breakpoints**:

| Breakpoint | Screen Size |
| ---------- | ----------- |
| sm         | ≥576px      |
| md         | ≥768px      |
| lg         | ≥992px      |
| xl         | ≥1200px     |

---

### 💡 Example

```html
<div class="col-12 col-sm-6 col-lg-3">
  Responsive Box
</div>
```

---

## 📌 Task

Create a responsive webpage that includes:

* Bootstrap CDN integration
* Grid layout (12-column system)
* At least 2 components (card, button, navbar)
* Responsive behavior across devices

---

## ✅ Summary

* Bootstrap is a powerful front-end framework
* It uses a **12-column grid system**
* Classes control layout, spacing, and styling
* Easily integrated using CDN or download
* Helps build responsive websites quickly

---

## 🧠 Final Outcomes

Trainees will be able to:

* Use Bootstrap in real projects
* Build responsive layouts using grid system
* Apply utility classes effectively
* Create UI components بسرعة واحترافية

---

## 💡 What I Know Now

* [ ] I understand what Bootstrap is and why it is used
* [ ] I can use the **12-column grid system**
* [ ] I know how to add Bootstrap via CDN
* [ ] I can use Bootstrap classes for styling
* [ ] I can build responsive layouts using Bootstrap

---

### 📙 References

1. Bootstrap Official Docs: [https://getbootstrap.com](https://getbootstrap.com)
2. Bootstrap Grid System: [https://getbootstrap.com/docs/5.3/layout/grid/](https://getbootstrap.com/docs/5.3/layout/grid/)
3. Bootstrap Utilities: [https://getbootstrap.com/docs/5.3/utilities/](https://getbootstrap.com/docs/5.3/utilities/)

---

👉 **Go to Next Module → Advanced Bootstrap & Layouts**

```
```
