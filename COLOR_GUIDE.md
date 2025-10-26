# RANS HOTEL - Color Scheme Guide

## 🎨 **Primary Color Palette**

### **Gold (Primary)**
- **Primary Gold**: `#ffce14` - Main brand color
- **Gold Dark**: `#e6b800` - Hover states, active elements
- **Gold Light**: `#ffd633` - Light accents

### **Blue (Secondary)**
- **Secondary Blue**: `#2c3e50` - Text, headers, navigation
- **Blue Light**: `#34495e` - Secondary backgrounds
- **Blue Dark**: `#1a252f` - Dark sections, sidebars

### **Orange (Accent)**
- **Accent Orange**: `#ff8c00` - Call-to-action elements
- **Orange Light**: `#ffa500` - Light accents
- **Orange Dark**: `#e67e00` - Hover states

## 🎯 **Usage Guidelines**

### **Primary Gold (#ffce14)**
- ✅ Buttons and call-to-action elements
- ✅ Brand highlights and accents
- ✅ Star ratings and reviews
- ✅ Form focus states
- ✅ Pricing displays

### **Secondary Blue (#2c3e50)**
- ✅ Main text color
- ✅ Headers and titles
- ✅ Navigation backgrounds
- ✅ Form labels
- ✅ Table headers

### **Accent Orange (#ff8c00)**
- ✅ Hover states for gold elements
- ✅ Secondary buttons
- ✅ Warning states
- ✅ Progress indicators

## 📱 **Component Color Mapping**

### **Navigation**
- Background: `var(--secondary-blue)`
- Text: `var(--text-light)`
- Hover: `var(--primary-gold)`
- Active: `var(--primary-gold)`

### **Buttons**
- Primary: `var(--gradient-primary)`
- Hover: `linear-gradient(135deg, var(--primary-gold-dark) 0%, var(--accent-orange-dark) 100%)`
- Text: `var(--text-light)`

### **Forms**
- Labels: `var(--text-primary)`
- Focus: `var(--primary-gold)`
- Background: `var(--neutral-light)`

### **Cards & Panels**
- Background: `var(--neutral-white)`
- Border: `var(--primary-gold)`
- Header: `var(--gradient-primary)`

### **Alerts & Notifications**
- Success: `var(--success-green)`
- Error: `var(--error-red)`
- Warning: `var(--warning-yellow)`
- Info: `var(--info-blue)`

## 🌈 **Gradients**

### **Primary Gradient**
```css
background: linear-gradient(135deg, var(--primary-gold) 0%, var(--accent-orange) 100%);
```

### **Secondary Gradient**
```css
background: linear-gradient(135deg, var(--secondary-blue) 0%, var(--secondary-blue-light) 100%);
```

### **Hero Gradient**
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

## 📋 **CSS Variables Reference**

```css
:root {
    /* Primary Colors */
    --primary-gold: #ffce14;
    --primary-gold-dark: #e6b800;
    --primary-gold-light: #ffd633;
    
    /* Secondary Colors */
    --secondary-blue: #2c3e50;
    --secondary-blue-light: #34495e;
    --secondary-blue-dark: #1a252f;
    
    /* Accent Colors */
    --accent-orange: #ff8c00;
    --accent-orange-light: #ffa500;
    --accent-orange-dark: #e67e00;
    
    /* Neutral Colors */
    --neutral-white: #ffffff;
    --neutral-light: #f8f9fa;
    --neutral-gray: #6c757d;
    --neutral-dark: #343a40;
    
    /* Status Colors */
    --success-green: #28a745;
    --error-red: #dc3545;
    --warning-yellow: #ffc107;
    --info-blue: #17a2b8;
    
    /* Text Colors */
    --text-primary: var(--secondary-blue);
    --text-secondary: var(--neutral-gray);
    --text-light: var(--neutral-white);
    --text-accent: var(--primary-gold);
    
    /* Gradients */
    --gradient-primary: linear-gradient(135deg, var(--primary-gold) 0%, var(--accent-orange) 100%);
    --gradient-secondary: linear-gradient(135deg, var(--secondary-blue) 0%, var(--secondary-blue-light) 100%);
    --gradient-hero: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

## 🔧 **Implementation**

### **1. Include the Color CSS**
```html
<link href="css/hotel-colors.css" rel="stylesheet" type="text/css" media="all" />
```

### **2. Use CSS Variables**
```css
.my-button {
    background: var(--gradient-primary);
    color: var(--text-light);
}

.my-button:hover {
    background: linear-gradient(135deg, var(--primary-gold-dark) 0%, var(--accent-orange-dark) 100%);
}
```

### **3. Apply to Components**
- All buttons should use `var(--gradient-primary)`
- All text should use `var(--text-primary)`
- All backgrounds should use `var(--neutral-white)` or `var(--neutral-light)`
- All borders should use `var(--primary-gold)`

## 📱 **Responsive Considerations**

- Colors remain consistent across all screen sizes
- Hover states are maintained on touch devices
- Contrast ratios meet accessibility standards
- Color-blind friendly palette

## ♿ **Accessibility**

- All color combinations meet WCAG AA standards
- Sufficient contrast ratios for text readability
- Color is not the only way to convey information
- Alternative indicators for interactive elements

## 🎨 **Brand Consistency**

This color scheme represents:
- **Gold**: Luxury, warmth, premium service
- **Blue**: Trust, reliability, professionalism
- **Orange**: Energy, enthusiasm, hospitality

The combination creates a warm, welcoming, and professional appearance that reflects the quality of RansHotel's service.
