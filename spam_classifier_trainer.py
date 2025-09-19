import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, confusion_matrix, classification_report
import joblib
import os

# This is a sample dataset. In a real-world scenario, you would use a much larger, more comprehensive dataset
# of labeled connection data (e.g., from server logs).
# 'is_spam' is the target variable: 1 for spam, 0 for not spam.
data = {
    'user_agent': [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'python-requests/2.25.1',
        'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        'curl/7.64.1',
        'Scrapy/2.5.0 (+https://scrapy.org)',
        'Mozilla/5.0 (Linux; Android 10; SM-G975F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Mobile Safari/537.36',
        'Nmap Scripting Engine; http://nmap.org/book/nse.html',
        'masscan/1.3.2 (https://github.com/robertdavidgraham/masscan)',
        'zgrab/0.1.7',
        'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Mobile/15E148 Safari/604.1'
    ],
    'is_spam': [0, 1, 0, 1, 1, 0, 1, 1, 1, 0]
}

df = pd.DataFrame(data)

# Feature Extraction using TF-IDF
# TF-IDF is useful for converting text data like user agents into a meaningful representation of numbers.
vectorizer = TfidfVectorizer(analyzer='char', ngram_range=(2, 5))
X = vectorizer.fit_transform(df['user_agent'])
y = df['is_spam']

# Splitting the dataset for training and testing
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.3, random_state=42)

# Training the Logistic Regression model
model = LogisticRegression(random_state=42)
model.fit(X_train, y_train)

# Evaluating the model's performance
y_pred = model.predict(X_test)
print(f"Accuracy: {accuracy_score(y_test, y_pred)}")
print("Confusion Matrix:")
print(confusion_matrix(y_test, y_pred))
print("Classification Report:")
print(classification_report(y_test, y_pred))

# Create a directory to store the model and vectorizer
if not os.path.exists('model'):
    os.makedirs('model')

# Saving the trained model and the vectorizer for later use
joblib.dump(model, 'model/spam_classifier.pkl')
joblib.dump(vectorizer, 'model/vectorizer.pkl')

print("\nModel and vectorizer have been saved to the 'model' directory.")
