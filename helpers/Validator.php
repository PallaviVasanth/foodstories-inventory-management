<?php

/** Holds product input checks separately from controllers. A dedicated validator is easier to reuse and test than repeating checks in each endpoint. */

class Validator
{
    /** Returns the first validation message, or null when fields pass these checks. Manual validation suits this small project; a validation library is an alternative for larger rule sets. */

    public static function validateProduct($data)
    {
        if (empty($data['name'])) {
            return "Name is required";
        }

        if (empty($data['sku'])) {
            return "SKU is required";
        }

        if (!isset($data['category_id'])) {
            return "Category is required";
        }

        if (!isset($data['price'])) {
            return "Price is required";
        }

        if ($data['price'] < 0) {
            return "Price cannot be negative";
        }

        if (!isset($data['quantity'])) {
            return "Quantity is required";
        }

        if ($data['quantity'] < 0) {
            return "Quantity cannot be negative";
        }

        if (!isset($data['low_stock_threshold'])) {
            return "Low stock threshold is required";
        }

        if ($data['low_stock_threshold'] < 0) {
            return "Low stock threshold cannot be negative";
        }

        return null;
    }
}