// API SATWA ENDEMIK KALIMANTAN - PURE NODE.JS (TANPA DEPENDENCIES)
const http = require('http');
const url = require('url');

// Data Dummy (10 data)
const satwaData = [
    { id: 1, nama_latin: "Pongo pygmaeus", nama_umum: "Bornean Orangutan", nama: "Orangutan Kalimantan", status: "Kritis", gambar: "https://images.unsplash.com/photo-1551085254-e96b210db58a" },
    { id: 2, nama_latin: "Nasalis larvatus", nama_umum: "Proboscis Monkey", nama: "Bekantan", status: "Terancam", gambar: "https://images.unsplash.com/photo-1583511655857-d19b40a7a54e" },
    { id: 3, nama_latin: "Neofelis diardi", nama_umum: "Bornean Clouded Leopard", nama: "Macan Dahan", status: "Rentan", gambar: "https://images.unsplash.com/photo-1564349683136-77e08dba1ef7" },
    { id: 4, nama_latin: "Rhinoplax vigil", nama_umum: "Helmeted Hornbill", nama: "Enggang Gading", status: "Kritis", gambar: "https://images.unsplash.com/photo-1552728089-57bdde30beb3" },
    { id: 5, nama_latin: "Helarctos malayanus", nama_umum: "Sun Bear", nama: "Beruang Madu", status: "Rentan", gambar: "https://images.unsplash.com/photo-1574870111867-089858c7a7d8" },
    { id: 6, nama_latin: "Cervus unicolor", nama_umum: "Sambar Deer", nama: "Rusa Sambar", status: "Rendah", gambar: "https://images.unsplash.com/photo-1567954970774-58d6b4e8b4e2" },
    { id: 7, nama_latin: "Tragulus napu", nama_umum: "Greater Mouse-deer", nama: "Kancil", status: "Rendah", gambar: "https://images.unsplash.com/photo-1518837695005-2083093ee35b" },
    { id: 8, nama_latin: "Hylobates muelleri", nama_umum: "Bornean Gibbon", nama: "Owa Kalimantan", status: "Rentan", gambar: "https://images.unsplash.com/photo-1548685913-6e89c9c2a6c3" },
    { id: 9, nama_latin: "Lutra sumatrana", nama_umum: "Hairy-nosed Otter", nama: "Berang-berang", status: "Terancam", gambar: "https://images.unsplash.com/photo-1568145675395-66a2eda0c6d7" },
    { id: 10, nama_latin: "Pardofelis badia", nama_umum: "Bornean Bay Cat", nama: "Kucing Batu", status: "Rentan", gambar: "https://images.unsplash.com/photo-1514888286974-6d03bde4ba4f" }
];

// Create HTTP Server
const server = http.createServer((req, res) => {
    // Set CORS headers
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');
    res.setHeader('Content-Type', 'application/json');
    
    // Parse URL
    const parsedUrl = url.parse(req.url, true);
    const path = parsedUrl.pathname;
    const method = req.method;
    
    // Handle OPTIONS (CORS preflight)
    if (method === 'OPTIONS') {
        res.writeHead(200);
        res.end();
        return;
    }
    
    // ROUTES
    // Homepage
    if (path === '/' && method === 'GET') {
        res.writeHead(200);
        res.end(JSON.stringify({
            app: "🌿 SATKA API - Satwa Endemik Kalimantan",
            version: "1.0.0",
            message: "API berjalan dengan baik!",
            endpoints: {
                all_satwa: "/api/satwa",
                satwa_by_id: "/api/satwa/{id}",
                search: "/api/search?q={keyword}",
                stats: "/api/stats"
            }
        }));
    }
    
    // GET all satwa
    else if (path === '/api/satwa' && method === 'GET') {
        res.writeHead(200);
        res.end(JSON.stringify({
            success: true,
            count: satwaData.length,
            data: satwaData
        }));
    }
    
    // GET satwa by ID
    else if (path.startsWith('/api/satwa/') && method === 'GET') {
        const id = parseInt(path.split('/')[3]);
        const satwa = satwaData.find(s => s.id === id);
        
        if (satwa) {
            res.writeHead(200);
            res.end(JSON.stringify({
                success: true,
                data: satwa
            }));
        } else {
            res.writeHead(404);
            res.end(JSON.stringify({
                success: false,
                error: "Satwa tidak ditemukan"
            }));
        }
    }
    
    // SEARCH satwa
    else if (path === '/api/search' && method === 'GET') {
        const keyword = parsedUrl.query.q || '';
        const results = satwaData.filter(s => 
            s.nama.toLowerCase().includes(keyword.toLowerCase()) ||
            s.nama_umum.toLowerCase().includes(keyword.toLowerCase()) ||
            s.nama_latin.toLowerCase().includes(keyword.toLowerCase())
        );
        
        res.writeHead(200);
        res.end(JSON.stringify({
            success: true,
            keyword: keyword,
            count: results.length,
            data: results
        }));
    }
    
    // STATS
    else if (path === '/api/stats' && method === 'GET') {
        const stats = satwaData.reduce((acc, curr) => {
            acc[curr.status] = (acc[curr.status] || 0) + 1;
            return acc;
        }, {});
        
        res.writeHead(200);
        res.end(JSON.stringify({
            success: true,
            total: satwaData.length,
            stats: stats
        }));
    }
    
    // POST - Add new satwa
    else if (path === '/api/satwa' && method === 'POST') {
        let body = '';
        req.on('data', chunk => body += chunk);
        req.on('end', () => {
            try {
                const newSatwa = JSON.parse(body);
                newSatwa.id = satwaData.length + 1;
                satwaData.push(newSatwa);
                
                res.writeHead(201);
                res.end(JSON.stringify({
                    success: true,
                    message: "Satwa berhasil ditambahkan",
                    data: newSatwa
                }));
            } catch (error) {
                res.writeHead(400);
                res.end(JSON.stringify({
                    success: false,
                    error: "Data tidak valid"
                }));
            }
        });
    }
    
    // 404 Not Found
    else {
        res.writeHead(404);
        res.end(JSON.stringify({
            success: false,
            error: "Endpoint tidak ditemukan",
            path: path
        }));
    }
});

// Start Server
const PORT = 3000;
server.listen(PORT, () => {
    console.log(`✅ Server SATKA API berjalan di port ${PORT}`);
    console.log(`🌐 Local: http://localhost:${PORT}`);
    console.log(`📚 API Documentation:`);
    console.log(`   Home: http://localhost:${PORT}/`);
    console.log(`   All Satwa: http://localhost:${PORT}/api/satwa`);
    console.log(`   Search: http://localhost:${PORT}/api/search?q=orangutan`);
    console.log(`   Stats: http://localhost:${PORT}/api/stats`);
    console.log(`\n🚀 Untuk ngrok, jalankan di CMD baru:`);
    console.log(`   ngrok http ${PORT} --url assuring-quail-real.ngrok-free.app`);
});