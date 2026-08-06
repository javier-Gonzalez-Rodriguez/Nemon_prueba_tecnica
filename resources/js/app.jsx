import '../css/app.css';

import React from 'react';
import ReactDOM from 'react-dom/client';


function App() {
    return (
        <h1>¡React funciona dentro de Laravel!</h1>
    );
}

ReactDOM.createRoot(document.getElementById('app')).render(
    <App />
);