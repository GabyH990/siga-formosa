import './bootstrap';
import '../css/app.css';
import Swal from 'sweetalert2'

// Opcional: lo dejás global para usarlo en cualquier blade con scripts
window.Swal = Swal


import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
