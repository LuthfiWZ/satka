const formatResponse = {
    success: (data, message = 'Success', meta = {}) => ({
        success: true,
        message,
        data,
        ...meta
    }),

    error: (message = 'Error', errors = []) => ({
        success: false,
        message,
        errors: Array.isArray(errors) ? errors : [errors]
    }),

    paginated: (data, pagination, message = 'Success') => ({
        success: true,
        message,
        data,
        pagination
    })
};

module.exports = formatResponse;