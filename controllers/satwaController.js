const satwaModel = require('../models/satwaModel');

const satwaController = {
    // Get all satwa with pagination
    getAllSatwa: async (req, res) => {
        try {
            const page = parseInt(req.query.page) || 1;
            const limit = parseInt(req.query.limit) || 10;
            const search = req.query.search || '';
            const status = req.query.status || '';

            const satwa = await satwaModel.getAll(page, limit, search, status);
            const total = await satwaModel.getTotalCount(search, status);
            const totalPages = Math.ceil(total / limit);

            res.json({
                success: true,
                message: 'Data satwa berhasil diambil',
                data: satwa,
                pagination: {
                    page,
                    limit,
                    total,
                    totalPages,
                    hasNext: page < totalPages,
                    hasPrev: page > 1
                }
            });
        } catch (error) {
            res.status(500).json({
                success: false,
                message: 'Gagal mengambil data satwa',
                error: error.message
            });
        }
    },

    // Get satwa by ID
    getSatwaById: async (req, res) => {
        try {
            const satwa = await satwaModel.getById(req.params.id);
            
            if (!satwa) {
                return res.status(404).json({
                    success: false,
                    message: 'Satwa tidak ditemukan'
                });
            }

            res.json({
                success: true,
                message: 'Data satwa berhasil diambil',
                data: satwa
            });
        } catch (error) {
            res.status(500).json({
                success: false,
                message: 'Gagal mengambil data satwa',
                error: error.message
            });
        }
    },

    // Create new satwa
    createSatwa: async (req, res) => {
        try {
            // Basic validation
            const requiredFields = ['nama_latin', 'nama_umum', 'nama', 'status_konservasi'];
            const missingFields = requiredFields.filter(field => !req.body[field]);
            
            if (missingFields.length > 0) {
                return res.status(400).json({
                    success: false,
                    message: `Field berikut diperlukan: ${missingFields.join(', ')}`
                });
            }

            const result = await satwaModel.create(req.body);
            
            res.status(201).json({
                success: true,
                message: 'Satwa berhasil ditambahkan',
                data: result
            });
        } catch (error) {
            res.status(500).json({
                success: false,
                message: 'Gagal menambahkan satwa',
                error: error.message
            });
        }
    },

    // Update satwa
    updateSatwa: async (req, res) => {
        try {
            const result = await satwaModel.update(req.params.id, req.body);
            
            res.json({
                success: true,
                message: 'Satwa berhasil diperbarui',
                data: result
            });
        } catch (error) {
            const statusCode = error.message === 'Satwa tidak ditemukan' ? 404 : 500;
            res.status(statusCode).json({
                success: false,
                message: 'Gagal memperbarui satwa',
                error: error.message
            });
        }
    },

    // Delete satwa
    deleteSatwa: async (req, res) => {
        try {
            const result = await satwaModel.delete(req.params.id);
            
            res.json({
                success: true,
                message: 'Satwa berhasil dihapus',
                data: result
            });
        } catch (error) {
            const statusCode = error.message === 'Satwa tidak ditemukan' ? 404 : 500;
            res.status(statusCode).json({
                success: false,
                message: 'Gagal menghapus satwa',
                error: error.message
            });
        }
    },

    // Get satwa by status
    getSatwaByStatus: async (req, res) => {
        try {
            const status = req.params.status;
            const validStatuses = ['Rentan', 'Terancam', 'Kritis', 'Hampir Terancam', 'Risiko Rendah'];
            
            if (!validStatuses.includes(status)) {
                return res.status(400).json({
                    success: false,
                    message: 'Status konservasi tidak valid'
                });
            }

            const satwa = await satwaModel.getByStatus(status);
            
            res.json({
                success: true,
                message: `Data satwa dengan status ${status} berhasil diambil`,
                data: satwa
            });
        } catch (error) {
            res.status(500).json({
                success: false,
                message: 'Gagal mengambil data satwa',
                error: error.message
            });
        }
    },

    // Get random satwa for homepage
    getRandomSatwa: async (req, res) => {
        try {
            const limit = parseInt(req.query.limit) || 3;
            const satwa = await satwaModel.getRandom(limit);
            
            res.json({
                success: true,
                message: 'Data satwa acak berhasil diambil',
                data: satwa
            });
        } catch (error) {
            res.status(500).json({
                success: false,
                message: 'Gagal mengambil data satwa acak',
                error: error.message
            });
        }
    },

    // Search satwa
    searchSatwa: async (req, res) => {
        try {
            const keyword = req.query.q || '';
            
            if (!keyword.trim()) {
                return res.status(400).json({
                    success: false,
                    message: 'Keyword pencarian diperlukan'
                });
            }

            const results = await satwaModel.search(keyword);
            
            res.json({
                success: true,
                message: 'Hasil pencarian berhasil diambil',
                data: results,
                keyword: keyword
            });
        } catch (error) {
            res.status(500).json({
                success: false,
                message: 'Gagal melakukan pencarian',
                error: error.message
            });
        }
    },

    // Get all kategori
    getAllKategori: async (req, res) => {
        try {
            const kategori = await satwaModel.getAllKategori();
            
            res.json({
                success: true,
                message: 'Data kategori berhasil diambil',
                data: kategori
            });
        } catch (error) {
            res.status(500).json({
                success: false,
                message: 'Gagal mengambil data kategori',
                error: error.message
            });
        }
    },

    // Get stats
    getStats: async (req, res) => {
        try {
            const stats = await satwaModel.getStats();
            
            res.json({
                success: true,
                message: 'Statistik satwa berhasil diambil',
                data: stats
            });
        } catch (error) {
            res.status(500).json({
                success: false,
                message: 'Gagal mengambil statistik',
                error: error.message
            });
        }
    }
};

module.exports = satwaController;