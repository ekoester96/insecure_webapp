import joblib
import sys

def predict_spam(user_agent):
    """
    Predicts if a user agent string is from a spam connection.
    """
    try:
        # Load the pre-trained logistic regression model and TF-IDF vectorizer
        model = joblib.load('model/spam_classifier.pkl')
        vectorizer = joblib.load('model/vectorizer.pkl')

        # Transform the user agent using the loaded vectorizer
        user_agent_transformed = vectorizer.transform([user_agent])

        # Make a prediction
        prediction = model.predict(user_agent_transformed)
        probability = model.predict_proba(user_agent_transformed)

        # Return the prediction and the probability of it being spam
        return {
            'prediction': int(prediction[0]),
            'spam_probability': float(probability[0][1])
        }
    except FileNotFoundError:
        return {'error': 'Model or vectorizer not found. Please train the model first.'}
    except Exception as e:
        return {'error': str(e)}

if __name__ == "__main__":
    # The script expects the user agent string as a command-line argument
    if len(sys.argv) > 1:
        ua_string = sys.argv[1]
        result = predict_spam(ua_string)
        # Output result in a machine-readable format (JSON) for PHP to parse
        import json
        print(json.dumps(result))
