import '../css/app.css';

import React from 'react';
import ReactDOM from 'react-dom/client';

import { useState } from 'react';
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

        }catch(error){
            console.error(error);
            console.error(error.status);

            if(error.status == 400){
                setError(true);
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
                        <div><span className={formError ? 'error-title' : ''}>Formula</span>*</div>
                        <input type="text" name="formula" id="form-formula" onChange={handleChange} value={form.formula} />
                    </div>

                    <div className="row-container">
                        <div className="date-input-column">
                            <div><span className={formError ? 'error-title' : ''}>Start date</span>*</div>
                            <input type="date" name="start_date" id="form-start-date" onChange={handleChange} max={(form.end_date) || undefined} value={form.start_date} />
                        </div>
                        <div className="date-input-column">
                            <div><span className={formError ? 'error-title' : ''}>End date</span>*</div>
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