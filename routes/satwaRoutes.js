const express = require('express');
const router = express.Router();
const satwaController = require('../controllers/satwaController');

// Public routes
router.get('/', satwaController.getAllSatwa);
router.get('/random', satwaController.getRandomSatwa);
router.get('/search', satwaController.searchSatwa);
router.get('/status/:status', satwaController.getSatwaByStatus);
router.get('/kategori', satwaController.getAllKategori);
router.get('/stats', satwaController.getStats);

// Get specific satwa
router.get('/:id', satwaController.getSatwaById);

// Protected routes (CRUD operations)
router.post('/', satwaController.createSatwa);
router.put('/:id', satwaController.updateSatwa);
router.delete('/:id', satwaController.deleteSatwa);

module.exports = router;