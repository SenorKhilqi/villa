# 🎨 Villa Panjalu - Tailwind Migration Guide

## ✅ Yang Sudah Dikerjakan

### 1. Database - DONE ✅
- ✅ **villa_panjalu_complete.sql** - All-in-one schema
- ✅ Includes: tables, views, triggers, indexes
- ✅ Sample queries & documentation
- ✅ Ready for fresh install

### 2. Tailwind Setup - DONE ✅
- ✅ **includes/head.php** - Tailwind CDN v3.4
- ✅ Custom color scheme (primary, villa colors)
- ✅ Custom font (Poppins, Playfair Display)
- ✅ Font Awesome icons
- ✅ **css/common.css** - Legacy CSS untuk gradual migration

---

## 🎯 Tailwind Class Mapping Reference

### Colors
```
Old CSS Variable → Tailwind Class
--primary          → bg-primary / text-primary
--primary-dark     → bg-primary-dark
--primary-light    → bg-primary-light
--accent           → bg-accent / text-accent
--danger           → bg-red-500
--success          → bg-green-500
--warning          → bg-yellow-400
--gray-light       → bg-gray-50
```

### Common Patterns

#### 1. Buttons
```html
<!-- Old -->
<button class="btn btn-primary">Click</button>

<!-- New Tailwind -->
<button class="px-6 py-3 bg-gradient-to-r from-primary to-primary-dark text-white rounded-lg hover:shadow-lg transition-all">
    Click
</button>
```

#### 2. Cards
```html
<!-- Old -->
<div class="card">
    <div class="card-header">Title</div>
    <div class="card-body">Content</div>
</div>

<!-- New Tailwind -->
<div class="bg-white rounded-xl shadow-soft p-6 hover:shadow-medium transition-shadow">
    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Title</h3>
    </div>
    <div class="pt-4">Content</div>
</div>
```

#### 3. Status Badges
```html
<!-- Old -->
<span class="badge badge-success">Active</span>

<!-- New Tailwind -->
<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
    Active
</span>
```

#### 4. Forms
```html
<!-- Old -->
<input type="text" class="form-control" />

<!-- New Tailwind -->
<input type="text" 
    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all" 
/>
```

#### 5. Tables
```html
<!-- Old -->
<table class="table">
    <thead><tr><th>Name</th></tr></thead>
    <tbody><tr><td>Data</td></tr></tbody>
</table>

<!-- New Tailwind -->
<table class="w-full">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm">Data</td>
        </tr>
    </tbody>
</table>
```

#### 6. Grid Layouts
```html
<!-- Old CSS -->
.stats-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

<!-- New Tailwind -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- cards -->
</div>
```

---

## 📋 Migration Steps (per file)

### Step 1: Update Head Section
```php
<!-- Old -->
<head>
    <title>Page Title</title>
    <link href="fonts..." rel="stylesheet">
    <style>
        /* 500 lines of CSS */
    </style>
</head>

<!-- New -->
<head>
    <title>Page Title</title>
    <?php include 'includes/head.php'; ?>
    <!-- No more inline styles! -->
</head>
```

### Step 2: Convert Classes
1. Find all `class="old-class"`
2. Replace with Tailwind utilities
3. Remove `<style>` block
4. Test responsiveness

### Step 3: Test
- Desktop (1920px)
- Tablet (768px)
- Mobile (375px)

---

## 🚀 Quick Tailwind Cheatsheet

### Layout
```
Container:      max-w-7xl mx-auto px-4
Flex:           flex items-center justify-between gap-4
Grid:           grid grid-cols-3 gap-6
```

### Spacing
```
Padding:        p-4 (16px), p-6 (24px), p-8 (32px)
Margin:         m-4, mt-4, mb-4, mx-auto
Gap:            gap-2 (8px), gap-4 (16px), gap-6 (24px)
```

### Typography
```
Size:           text-sm, text-base, text-lg, text-xl, text-2xl
Weight:         font-normal, font-medium, font-semibold, font-bold
Color:          text-gray-900, text-primary, text-white
```

### Colors & Backgrounds
```
Background:     bg-white, bg-gray-50, bg-primary
Text:           text-gray-900, text-white
Border:         border border-gray-300
```

### Borders & Shadows
```
Radius:         rounded, rounded-lg, rounded-xl, rounded-full
Shadow:         shadow-sm, shadow, shadow-md, shadow-lg
Border:         border, border-2, border-t, border-b
```

### States
```
Hover:          hover:bg-primary hover:shadow-lg
Focus:          focus:ring-2 focus:ring-primary
Active:         active:scale-95
Disabled:       disabled:opacity-50 disabled:cursor-not-allowed
```

### Responsive
```
Mobile first:   
- Default = mobile
- md: = tablet (768px+)
- lg: = desktop (1024px+)
- xl: = large desktop (1280px+)

Example:
class="text-sm md:text-base lg:text-lg"
class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4"
```

---

## 🎨 Custom Tailwind Classes (in head.php config)

```javascript
colors: {
    primary: {
        DEFAULT: '#B0A695',
        dark: '#8A7F6C',
        light: '#EBE3D5',
    },
    accent: '#776B5D',
    villa: {
        50: '#F8F7F5',
        100: '#EBE3D5',
        // ... 200-900
    }
}
```

Usage:
```html
<div class="bg-primary text-white">Primary</div>
<div class="bg-villa-100 text-villa-900">Villa Colors</div>
```

---

## 📂 File Status

### ✅ Completed
- [x] `databases/villa_panjalu_complete.sql`
- [x] `includes/head.php`
- [x] `css/common.css`

### 🔄 In Progress
- [ ] `admin_dashboard.php` → Convert to Tailwind
- [ ] `refund_request.php` → Convert to Tailwind
- [ ] `booking.php` → Convert to Tailwind

### 📝 Next Steps
1. Convert admin_dashboard.php (prioritas tinggi - paling complex)
2. Convert refund_request.php
3. Convert booking.php
4. Update other pages gradually

---

## 💡 Tips

1. **Gunakan Tailwind classes langsung di HTML**
   ```html
   <div class="bg-white p-6 rounded-lg shadow-md">
   ```

2. **Untuk repeated components, bisa tetap pakai class di common.css**
   ```css
   .btn-primary {
       @apply px-6 py-3 bg-primary text-white rounded-lg hover:shadow-lg;
   }
   ```

3. **Test responsiveness dengan browser DevTools**
   - F12 → Toggle device toolbar
   - Test berbagai ukuran layar

4. **Gunakan Tailwind's JIT compiler di production**
   - File size lebih kecil
   - Hanya include class yang dipakai

---

## 🔗 Resources

- Tailwind Docs: https://tailwindcss.com/docs
- Tailwind Cheatsheet: https://nerdcave.com/tailwind-cheat-sheet
- Tailwind Play (test online): https://play.tailwindcss.com

---

**Last Updated:** November 8, 2025  
**Status:** Database ✅ | Tailwind Setup ✅ | Page Migration 🔄
