from flask import Flask, render_template, request, redirect, url_for
import os
import matplotlib.pyplot as plt
import pandas as pd
import uuid  # Para generar nombres únicos de archivos

app = Flask(__name__)

# Asegurarse de que la carpeta 'static/graphs' exista para guardar los gráficos
GRAPH_DIR = os.path.join('static', 'graphs')
if not os.path.exists(GRAPH_DIR):
    os.makedirs(GRAPH_DIR)

# Página principal: formulario para subir el CSV y seleccionar el tipo de gráfico
@app.route('/')
def index():
    return render_template('index.html')

# Ruta para procesar el archivo CSV subido y generar gráficos
@app.route('/upload', methods=['POST'])
def upload():
    if 'csv_file' not in request.files:
        return "No se encontró el archivo", 400

    file = request.files['csv_file']

    if file.filename == '':
        return "No se seleccionó ningún archivo", 400

    # Obtener el tipo de gráfico seleccionado en el formulario
    chart_type = request.form.get('chart_type', 'line')  # Por defecto línea

    try:
        # Leer el CSV directamente desde el objeto file
        df = pd.read_csv(file)
    except Exception as e:
        return f"Error al leer el CSV: {e}", 400

    # Lista para almacenar los nombres de los gráficos generados
    graphs = []

    # Determinar si existe alguna columna de tipo objeto (categórica)
    object_cols = df.select_dtypes(include=['object']).columns.tolist()
    if object_cols:
        # Usamos la primera columna de tipo objeto como eje x
        x_col = object_cols[0]
        x_values = df[x_col]
    else:
        # Si no hay columna categórica, se usa el índice
        x_col = 'Índice'
        x_values = df.index

    # Seleccionar las columnas numéricas a graficar
    numeric_cols = df.select_dtypes(include=['number']).columns.tolist()
    if not numeric_cols:
        return "El archivo no contiene columnas numéricas para graficar.", 400

    # Para cada columna numérica, generamos el gráfico según el tipo seleccionado
    for col in numeric_cols:
        plt.figure(figsize=(8, 5))

        if chart_type == 'line':
            # Gráfico de líneas
            plt.plot(x_values, df[col], marker='o', linestyle='-', color='skyblue')
            plt.xlabel(x_col)
            plt.title(f'Línea: {col}' + (f' vs {x_col}' if x_col != 'Índice' else ''))
        elif chart_type == 'bar':
            # Diagrama de barras
            plt.bar(x_values, df[col], color='lightgreen')
            plt.xlabel(x_col)
            plt.title(f'Barras: {col}' + (f' vs {x_col}' if x_col != 'Índice' else ''))
        elif chart_type == 'pie':
            # Diagrama de tortas (pie)
            # Se recomienda usar un eje categórico para las etiquetas
            if x_col != 'Índice':
                plt.pie(df[col], labels=x_values, autopct='%1.1f%%', startangle=90)
                plt.title(f'Torta: {col}')
            else:
                # Si no hay columna categórica, se utiliza el índice como etiqueta
                plt.pie(df[col], labels=df.index, autopct='%1.1f%%', startangle=90)
                plt.title(f'Torta: {col}')
        else:
            return f"Tipo de gráfico '{chart_type}' no soportado.", 400

        plt.tight_layout()

        # Generar un nombre único para el archivo de imagen
        filename = f"{col}_{chart_type}_{uuid.uuid4().hex}.png"
        graph_path = os.path.join(GRAPH_DIR, filename)
        plt.savefig(graph_path)
        plt.close()

        # Guardar solo el nombre del archivo
        graphs.append(filename)

    # Renderizar el template con la lista de nombres de archivos
    return render_template('graphs.html', graphs=graphs, columns=df.columns.tolist(), 
                           x_col=x_col, chart_type=chart_type)

if __name__ == '__main__':
    app.run(debug=True)
