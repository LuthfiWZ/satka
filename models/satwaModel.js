const pool = require('../database');

const satwaModel = {
    // Get all satwa with categories
    getAll: async (page = 1, limit = 10, search = '', status = '') => {
        const offset = (page - 1) * limit;
        let query = `
            SELECT 
                s.*, 
                GROUP_CONCAT(DISTINCT k.nama_kategori) as kategori,
                GROUP_CONCAT(DISTINCT k.id) as kategori_ids
            FROM satwa s
            LEFT JOIN satwa_kategori sk ON s.id = sk.satwa_id
            LEFT JOIN kategori k ON sk.kategori_id = k.id
        `;
        
        const conditions = [];
        const params = [];
        
        if (search) {
            conditions.push(`(s.nama LIKE ? OR s.nama_umum LIKE ? OR s.nama_latin LIKE ?)`);
            params.push(`%${search}%`, `%${search}%`, `%${search}%`);
        }
        
        if (status) {
            conditions.push(`s.status_konservasi = ?`);
            params.push(status);
        }
        
        if (conditions.length > 0) {
            query += ` WHERE ${conditions.join(' AND ')}`;
        }
        
        query += ` GROUP BY s.id ORDER BY s.id LIMIT ? OFFSET ?`;
        params.push(limit, offset);
        
        const [rows] = await pool.query(query, params);
        return rows;
    },

    // Get total count for pagination
    getTotalCount: async (search = '', status = '') => {
        let query = `SELECT COUNT(DISTINCT s.id) as total FROM satwa s`;
        const conditions = [];
        const params = [];
        
        if (search) {
            conditions.push(`(s.nama LIKE ? OR s.nama_umum LIKE ? OR s.nama_latin LIKE ?)`);
            params.push(`%${search}%`, `%${search}%`, `%${search}%`);
        }
        
        if (status) {
            conditions.push(`s.status_konservasi = ?`);
            params.push(status);
        }
        
        if (conditions.length > 0) {
            query += ` WHERE ${conditions.join(' AND ')}`;
        }
        
        const [rows] = await pool.query(query, params);
        return rows[0].total;
    },

    // Get satwa by ID
    getById: async (id) => {
        const [rows] = await pool.query(`
            SELECT 
                s.*, 
                GROUP_CONCAT(DISTINCT k.nama_kategori) as kategori,
                GROUP_CONCAT(DISTINCT k.id) as kategori_ids
            FROM satwa s
            LEFT JOIN satwa_kategori sk ON s.id = sk.satwa_id
            LEFT JOIN kategori k ON sk.kategori_id = k.id
            WHERE s.id = ?
            GROUP BY s.id
        `, [id]);
        return rows[0];
    },

    // Create new satwa
    create: async (satwaData) => {
        const {
            nama_latin,
            nama_umum,
            nama,
            deskripsi,
            habitat,
            makanan,
            status_konservasi,
            ancaman,
            upaya_konservasi,
            gambar_url,
            kategori_ids
        } = satwaData;

        const connection = await pool.getConnection();
        try {
            await connection.beginTransaction();

            // Insert satwa
            const [result] = await connection.query(`
                INSERT INTO satwa 
                (nama_latin, nama_umum, nama, deskripsi, habitat, makanan, status_konservasi, ancaman, upaya_konservasi, gambar_url)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            `, [nama_latin, nama_umum, nama, deskripsi, habitat, makanan, status_konservasi, ancaman, upaya_konservasi, gambar_url]);

            const satwaId = result.insertId;

            // Insert kategori relations if provided
            if (kategori_ids && Array.isArray(kategori_ids)) {
                const kategoriValues = kategori_ids.map(kategoriId => [satwaId, kategoriId]);
                if (kategoriValues.length > 0) {
                    await connection.query(
                        'INSERT INTO satwa_kategori (satwa_id, kategori_id) VALUES ?',
                        [kategoriValues]
                    );
                }
            }

            await connection.commit();
            return { id: satwaId, message: 'Satwa berhasil ditambahkan' };
        } catch (error) {
            await connection.rollback();
            throw error;
        } finally {
            connection.release();
        }
    },

    // Update satwa
    update: async (id, satwaData) => {
        const {
            nama_latin,
            nama_umum,
            nama,
            deskripsi,
            habitat,
            makanan,
            status_konservasi,
            ancaman,
            upaya_konservasi,
            gambar_url,
            kategori_ids
        } = satwaData;

        const connection = await pool.getConnection();
        try {
            await connection.beginTransaction();

            // Update satwa
            const [result] = await connection.query(`
                UPDATE satwa 
                SET nama_latin = ?, nama_umum = ?, nama = ?, deskripsi = ?, habitat = ?, makanan = ?, 
                    status_konservasi = ?, ancaman = ?, upaya_konservasi = ?, gambar_url = ?
                WHERE id = ?
            `, [nama_latin, nama_umum, nama, deskripsi, habitat, makanan, status_konservasi, ancaman, upaya_konservasi, gambar_url, id]);

            if (result.affectedRows === 0) {
                throw new Error('Satwa tidak ditemukan');
            }

            // Update kategori relations if provided
            if (kategori_ids) {
                // Delete existing relations
                await connection.query('DELETE FROM satwa_kategori WHERE satwa_id = ?', [id]);
                
                // Insert new relations if array is not empty
                if (Array.isArray(kategori_ids) && kategori_ids.length > 0) {
                    const kategoriValues = kategori_ids.map(kategoriId => [id, kategoriId]);
                    await connection.query(
                        'INSERT INTO satwa_kategori (satwa_id, kategori_id) VALUES ?',
                        [kategoriValues]
                    );
                }
            }

            await connection.commit();
            return { message: 'Satwa berhasil diperbarui' };
        } catch (error) {
            await connection.rollback();
            throw error;
        } finally {
            connection.release();
        }
    },

    // Delete satwa
    delete: async (id) => {
        const [result] = await pool.query('DELETE FROM satwa WHERE id = ?', [id]);
        if (result.affectedRows === 0) {
            throw new Error('Satwa tidak ditemukan');
        }
        return { message: 'Satwa berhasil dihapus' };
    },

    // Get satwa by status konservasi
    getByStatus: async (status) => {
        const [rows] = await pool.query(`
            SELECT 
                s.*, 
                GROUP_CONCAT(DISTINCT k.nama_kategori) as kategori
            FROM satwa s
            LEFT JOIN satwa_kategori sk ON s.id = sk.satwa_id
            LEFT JOIN kategori k ON sk.kategori_id = k.id
            WHERE s.status_konservasi = ?
            GROUP BY s.id
            ORDER BY s.nama
        `, [status]);
        return rows;
    },

    // Get random satwa for homepage
    getRandom: async (limit = 3) => {
        const [rows] = await pool.query(`
            SELECT 
                s.id, 
                s.nama, 
                s.nama_umum, 
                s.nama_latin, 
                s.status_konservasi,
                s.gambar_url,
                s.deskripsi,
                GROUP_CONCAT(DISTINCT k.nama_kategori) as kategori
            FROM satwa s
            LEFT JOIN satwa_kategori sk ON s.id = sk.satwa_id
            LEFT JOIN kategori k ON sk.kategori_id = k.id
            GROUP BY s.id
            ORDER BY RAND()
            LIMIT ?
        `, [limit]);
        return rows;
    },

    // Search satwa
    search: async (keyword) => {
        const [rows] = await pool.query(`
            SELECT 
                s.id, 
                s.nama, 
                s.nama_umum, 
                s.nama_latin, 
                s.status_konservasi,
                s.gambar_url,
                s.deskripsi
            FROM satwa s
            WHERE s.nama LIKE ? 
               OR s.nama_umum LIKE ? 
               OR s.nama_latin LIKE ? 
               OR s.deskripsi LIKE ?
            LIMIT 10
        `, [`%${keyword}%`, `%${keyword}%`, `%${keyword}%`, `%${keyword}%`]);
        return rows;
    },

    // Get all kategori
    getAllKategori: async () => {
        const [rows] = await pool.query('SELECT * FROM kategori ORDER BY nama_kategori');
        return rows;
    },

    // Get stats
    getStats: async () => {
        const [stats] = await pool.query(`
            SELECT 
                status_konservasi,
                COUNT(*) as jumlah,
                ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM satwa), 2) as persentase
            FROM satwa
            GROUP BY status_konservasi
            ORDER BY FIELD(status_konservasi, 'Kritis', 'Terancam', 'Rentan', 'Hampir Terancam', 'Risiko Rendah')
        `);
        return stats;
    }
};

module.exports = satwaModel;