from flask import Flask, request, jsonify, render_template
import google.generativeai as genai
from flask_cors import CORS

app = Flask(__name__)

# Enable CORS for the Flask app
CORS(app)

# Configure the Google Generative AI (Replace with your API key)
genai.configure(api_key="")
mymodel = genai.GenerativeModel("gemini-1.5-flash")
chat = mymodel.start_chat()

# Serve the HTML file (ai.html)
@app.route('/')
def index():
    return render_template('ai.html')

# Handle chat requests
@app.route('/chat', methods=['POST'])
def chat_endpoint():
    # Get the question from the request
    question = request.json.get('question', '')
    
    # Check if the question is empty
    if not question:
        return jsonify({'error': 'No question provided'}), 400

    try:
        # Get response from AI model
        response = chat.send_message(question + " .answer within 200 characters")
        return jsonify({'response': response.text})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
