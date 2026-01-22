const validateSatwa = (req, res, next) => {
    const { nama_latin, nama_umum, nama, status_konservasi } = req.body;
    
    const errors = [];
    
    if (!nama_latin) errors.push('Nama latin diperlukan');
    if (!nama_umum) errors.push('Nama umum diperlukan');
    if (!nama) errors.push('Nama lokal diperlukan');
    if (!status_konservasi) errors.push('Status konservasi diperlukan');
    
    if (status_konservasi && !['Rentan', 'Terancam', 'Kritis', 'Hampir Terancam', 'Risiko Rendah'].includes(status_konservasi)) {
        errors.push('Status konservasi tidak valid');
    }
    
    if (errors.length > 0) {
        return res.status(400).json({
            success: false,
            message: 'Validasi gagal',
            errors: errors
        });
    }
    
    next();
};

module.exports = {
    validateSatwa
};