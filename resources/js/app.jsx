import '../css/app.css';

import React from 'react';
import ReactDOM from 'react-dom/client';

import { useState, useRef, useEffect } from 'react';
import axios from 'axios';

function App() {
    //guarda los resultados de las operaciones previsas del usuario
    const [results, setResults] = useState([]);

    //contiene los datos del formulario
    const [form, setForm] = useState({
        start_date: '',
        end_date: '',
        formula: '',
    });

    //si el formulario contiene un error entonces error = true y cambia el color de los titulos a rojo
    const [formError, setError] = useState(false);

    //si no existe alguna fecha en el rango especificado entonces se pone rojo solo el texto de la fecha
    const [formErrorDates, setErrorDates] = useState(false);

    /**
     * actualiza los datos del formulario cuando el usuario realiza una modificación
     * @param {Event} e 
     */
    const handleChange = (e) => {
        setForm({
            ...form,
            [e.target.name]: e.target.value
        });
    };

    /**
     * llama a la api para recuperar el resultado de la operación
     * @param {Event} e evento que lanza la llamada a la api para obtener el resultado de la operación
     */
    const calculate = async (e) => {
        e.preventDefault();
        try{
            const request = await axios.post('/api/calculate', form);

            setResults([
                {
                    start_date: form.start_date,
                    end_date: form.end_date,
                    formula: form.formula,
                    result: request.data.price_indexed,
                },
                ...results,
            ]);
            
            setForm({
                start_date: '',
                end_date: '',
                formula: ''
            });

            setError(false);
            setErrorDates(false);
        }catch(error){
            if(error.status == 400){
                setError(true);
            }else if(error.status == 404){
                setErrorDates(true);
            }
        }
    };

    return (
        <div className="calculator-body">
            <div className="results-viewer">
                <h1>RESULTS</h1>
                {results.map((result, index) => (
                            <div className="preview-item" key={index}>
                                <span>{result.start_date} - {result.end_date}</span>
                                <span>{result.formula} = <b>{result.result}</b></span>
                            </div>
                        )
                    )
                }
            </div>

            <div className="calculator-card-container">
                <form onSubmit={calculate} className="calculator-card">
                    <div className="formula-column-container" id="card-preview">
                        <div>
                            <a href="/dataviewer" target="_blank">
                                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 17V11" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                                    <circle cx="1" cy="1" r="1" transform="matrix(1 0 0 -1 11 9)" fill="#1C274C"/>
                                    <path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                                Consult prices
                            </a>
                            
                        </div>
                        <div><span className={formError ? 'error-title' : ''}>Formula</span>*</div>
                        <input type="text" name="formula" id="form-formula" onChange={handleChange} value={form.formula} />
                    </div>

                    <div className="row-container">
                        <div className="date-input-column">
                            <div><span className={(formError || formErrorDates) ? 'error-title' : ''}>Start date</span>*</div>
                            <input type="date" name="start_date" id="form-start-date" onChange={handleChange} max={(form.end_date) || undefined} value={form.start_date} />
                        </div>
                        <div className="date-input-column">
                            <div><span className={(formError || formErrorDates) ? 'error-title' : ''}>End date</span>*</div>
                            <input type="date" name="end_date" id="form-end-date" onChange={handleChange} min={(form.start_date) || undefined} value={form.end_date} />
                        </div>
                    </div>

                    <button type='submit'>CALCULATE</button>

                    <div className="operator-legend">
                        <span>Operator legend</span>
                        <div className='valid-operator-container'>
                            <span className="legend">+    addition</span>
                            <span className="legend">-    subtraction</span>
                            <span className="legend">*    multiplication</span>
                            <span className="legend">/    division</span>
                            <span className="legend">%    mod (remainder)</span>
                            <span className="legend">**   exponentiation</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    );
}

ReactDOM.createRoot(document.getElementById('app')).render(
    <App />
);