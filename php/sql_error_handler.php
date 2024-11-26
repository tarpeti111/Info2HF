<?php function getErrorMessage(PDOException $e) {
    $errorMessages = [
        // Syntax or access violations
        '42S02' => "The requested table or view does not exist. Please contact support.", // Table not found
        '42S22' => "A column specified in the query does not exist. Please check your inputs.", // Column not found
        '42000' => "A syntax error occurred in the SQL query. Please contact support.", // Syntax error or access violation

        // Constraint violations
        '23000' => "A record with similar information already exists. Please check your input.", // Integrity constraint violation (e.g., duplicate entry)
        '23502' => "A required field is missing. Please ensure all fields are filled correctly.", // Not null violation
        '23503' => "The operation failed due to a reference constraint. Please check related records.", // Foreign key violation
        '23505' => "This operation would create a duplicate record. Please verify your data.", // Unique constraint violation

        // Authentication and permissions
        '28000' => "Authentication failed. Please check your database credentials.", // Invalid authorization specification
        '42001' => "You do not have the necessary permissions to perform this operation.", // Insufficient privileges

        // Connection-related errors
        '08001' => "Unable to establish a connection to the database. Please try again later.", // Connection exception
        '08004' => "The database server rejected the connection request. Please contact support.", // SQL server rejected connection
        '08006' => "The connection to the database was lost. Please try again.", // Connection failure

        // Data type errors
        '22007' => "Invalid date format. Please use the correct format (e.g., YYYY-MM-DD).", // Invalid datetime format
        '22003' => "A number in the input is out of range. Please enter a valid number.", // Numeric value out of range
        '22001' => "A value is too long for a field. Please shorten your input.", // String data right truncation

        // Transaction-related errors
        '40001' => "A transaction deadlock occurred. Please retry the operation.", // Serialization failure (deadlock)
        '40P01' => "A deadlock was detected while processing your request. Please try again.", // Deadlock detected

        // Default fallback
        'default' => "An unexpected error occurred. Please try again later."
    ];

    // Return the user-friendly message for the error code, or the default if not found
    return $errorMessages[$e->getCode()] ?? $errorMessages['default'];
}