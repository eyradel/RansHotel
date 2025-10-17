<?php
/**
 * Professional Form Components
 * RansHotel Admin System
 */

/**
 * Generate a professional form group
 */
function formGroup($label, $name, $type = 'text', $value = '', $required = false, $options = []) {
    $requiredAttr = $required ? 'required' : '';
    $placeholder = isset($options['placeholder']) ? $options['placeholder'] : '';
    $helpText = isset($options['help']) ? $options['help'] : '';
    $class = isset($options['class']) ? $options['class'] : '';
    
    $html = '<div class="form-group">';
    $html .= '<label for="' . $name . '" class="form-label">' . $label;
    if ($required) {
        $html .= ' <span class="text-danger">*</span>';
    }
    $html .= '</label>';
    
    if ($type === 'select') {
        $html .= '<select name="' . $name . '" id="' . $name . '" class="form-control ' . $class . '" ' . $requiredAttr . '>';
        if (isset($options['empty_option'])) {
            $html .= '<option value="">' . $options['empty_option'] . '</option>';
        }
        if (isset($options['options'])) {
            foreach ($options['options'] as $optionValue => $optionText) {
                $selected = ($value == $optionValue) ? 'selected' : '';
                $html .= '<option value="' . htmlspecialchars($optionValue) . '" ' . $selected . '>' . htmlspecialchars($optionText) . '</option>';
            }
        }
        $html .= '</select>';
    } elseif ($type === 'textarea') {
        $rows = isset($options['rows']) ? $options['rows'] : 4;
        $html .= '<textarea name="' . $name . '" id="' . $name . '" class="form-control ' . $class . '" rows="' . $rows . '" placeholder="' . $placeholder . '" ' . $requiredAttr . '>' . htmlspecialchars($value) . '</textarea>';
    } else {
        $html .= '<input type="' . $type . '" name="' . $name . '" id="' . $name . '" class="form-control ' . $class . '" value="' . htmlspecialchars($value) . '" placeholder="' . $placeholder . '" ' . $requiredAttr . '>';
    }
    
    if ($helpText) {
        $html .= '<small class="form-text text-muted">' . $helpText . '</small>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Generate a professional button
 */
function formButton($text, $type = 'submit', $class = 'btn-primary', $icon = '', $options = []) {
    $disabled = isset($options['disabled']) && $options['disabled'] ? 'disabled' : '';
    $onclick = isset($options['onclick']) ? 'onclick="' . $options['onclick'] . '"' : '';
    
    $html = '<button type="' . $type . '" class="btn ' . $class . '" ' . $disabled . ' ' . $onclick . '>';
    if ($icon) {
        $html .= '<i class="fa ' . $icon . ' mr-2"></i>';
    }
    $html .= $text;
    $html .= '</button>';
    
    return $html;
}

/**
 * Generate a professional card
 */
function formCard($title, $content, $footer = '', $class = '') {
    $html = '<div class="card ' . $class . '">';
    
    if ($title) {
        $html .= '<div class="card-header">';
        $html .= '<h3 class="card-title mb-0">' . $title . '</h3>';
        $html .= '</div>';
    }
    
    $html .= '<div class="card-body">';
    $html .= $content;
    $html .= '</div>';
    
    if ($footer) {
        $html .= '<div class="card-footer">';
        $html .= $footer;
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Generate a professional alert
 */
function formAlert($message, $type = 'info', $dismissible = true) {
    $dismissibleClass = $dismissible ? 'alert-dismissible fade show' : '';
    $dismissibleButton = $dismissible ? '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>' : '';
    
    $html = '<div class="alert alert-' . $type . ' ' . $dismissibleClass . '">';
    $html .= $message;
    $html .= $dismissibleButton;
    $html .= '</div>';
    
    return $html;
}

/**
 * Generate a professional table
 */
function formTable($headers, $data, $actions = [], $class = '') {
    $html = '<div class="table-responsive">';
    $html .= '<table class="table table-hover ' . $class . '">';
    
    // Table header
    $html .= '<thead>';
    $html .= '<tr>';
    foreach ($headers as $header) {
        $html .= '<th>' . $header . '</th>';
    }
    if (!empty($actions)) {
        $html .= '<th>Actions</th>';
    }
    $html .= '</tr>';
    $html .= '</thead>';
    
    // Table body
    $html .= '<tbody>';
    foreach ($data as $row) {
        $html .= '<tr>';
        foreach ($row as $cell) {
            $html .= '<td>' . $cell . '</td>';
        }
        if (!empty($actions)) {
            $html .= '<td>';
            foreach ($actions as $action) {
                $html .= $action;
            }
            $html .= '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    
    $html .= '</table>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Generate a professional modal
 */
function formModal($id, $title, $content, $footer = '', $size = '') {
    $sizeClass = $size ? 'modal-' . $size : '';
    
    $html = '<div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog">';
    $html .= '<div class="modal-dialog ' . $sizeClass . '" role="document">';
    $html .= '<div class="modal-content">';
    
    // Modal header
    $html .= '<div class="modal-header">';
    $html .= '<h4 class="modal-title">' . $title . '</h4>';
    $html .= '<button type="button" class="close" data-dismiss="modal">';
    $html .= '<span>&times;</span>';
    $html .= '</button>';
    $html .= '</div>';
    
    // Modal body
    $html .= '<div class="modal-body">';
    $html .= $content;
    $html .= '</div>';
    
    // Modal footer
    if ($footer) {
        $html .= '<div class="modal-footer">';
        $html .= $footer;
        $html .= '</div>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Generate a professional stats card
 */
function statsCard($title, $value, $icon, $color = 'primary', $trend = '') {
    $html = '<div class="stats-card" style="background: linear-gradient(135deg, var(--' . $color . '-color), var(--' . $color . '-color-dark));">';
    $html .= '<div class="d-flex justify-content-between align-items-center">';
    $html .= '<div>';
    $html .= '<h3 class="mb-1">' . $value . '</h3>';
    $html .= '<p class="mb-0">' . $title . '</p>';
    if ($trend) {
        $html .= '<small class="text-muted">' . $trend . '</small>';
    }
    $html .= '</div>';
    $html .= '<div class="stats-icon">';
    $html .= '<i class="fa ' . $icon . ' fa-2x"></i>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Generate a professional breadcrumb
 */
function breadcrumb($items) {
    $html = '<nav aria-label="breadcrumb">';
    $html .= '<ol class="breadcrumb">';
    
    $count = count($items);
    foreach ($items as $index => $item) {
        $isLast = ($index === $count - 1);
        $class = $isLast ? 'breadcrumb-item active' : 'breadcrumb-item';
        
        if ($isLast) {
            $html .= '<li class="' . $class . '">' . $item['text'] . '</li>';
        } else {
            $html .= '<li class="' . $class . '"><a href="' . $item['url'] . '">' . $item['text'] . '</a></li>';
        }
    }
    
    $html .= '</ol>';
    $html .= '</nav>';
    
    return $html;
}

/**
 * Generate a professional page header
 */
function pageHeader($title, $subtitle = '', $actions = '', $breadcrumb = '') {
    $html = '<div class="d-flex justify-content-between align-items-center mb-4">';
    $html .= '<div>';
    if ($breadcrumb) {
        $html .= $breadcrumb;
    }
    $html .= '<h1 class="mb-1">' . $title . '</h1>';
    if ($subtitle) {
        $html .= '<p class="text-muted mb-0">' . $subtitle . '</p>';
    }
    $html .= '</div>';
    if ($actions) {
        $html .= '<div class="d-flex align-items-center">';
        $html .= $actions;
        $html .= '</div>';
    }
    $html .= '</div>';
    
    return $html;
}
?>
