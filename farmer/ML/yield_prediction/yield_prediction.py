import pandas as pd
import numpy as np
import json
import sys
from sklearn.ensemble import RandomForestRegressor
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import OneHotEncoder

# Load and prepare data
df = pd.read_csv("ML/yield_prediction/crop_production_maharashtra_cleaned.csv")
df = df.drop(['Crop_Year'], axis=1)

# Clean up season values
df['Season'] = df['Season'].str.strip()

# Split features and target
X = df.drop(['Production'], axis=1)
y = df['Production']

# Split data
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Prepare categorical features
categorical_cols = ['State_Name', 'District_Name', 'Season', 'Crop']
ohe = OneHotEncoder(handle_unknown='ignore', sparse=False)
ohe.fit(X_train[categorical_cols])

# Transform training data
X_train_categorical = ohe.transform(X_train[categorical_cols])
X_test_categorical = ohe.transform(X_test[categorical_cols])

X_train_final = np.hstack((X_train_categorical, X_train.drop(categorical_cols, axis=1)))
X_test_final = np.hstack((X_test_categorical, X_test.drop(categorical_cols, axis=1)))

# Train model
model = RandomForestRegressor(n_estimators=100, random_state=42)
model.fit(X_train_final, y_train)

try:
    # Parse input
    if len(sys.argv) != 6:
        raise ValueError("Incorrect number of arguments")
        
    # Get input values
    state = sys.argv[1].strip('"\'')
    district = sys.argv[2].strip('"\'')
    season = sys.argv[3].strip('"\'')
    crop = sys.argv[4].strip('"\'')
    area = float(sys.argv[5].strip('"\''))
    
    # Validate inputs exist in dataset
    if state not in df['State_Name'].unique():
        raise ValueError(f"Invalid state: {state}")
    if district not in df['District_Name'].unique():
        raise ValueError(f"Invalid district: {district}")
    if season.strip() not in df['Season'].unique():
        raise ValueError(f"Invalid season: {season}")
    if crop not in df['Crop'].unique():
        raise ValueError(f"Invalid crop: {crop}")
    if area <= 0:
        raise ValueError(f"Area must be positive")
    
    # Create input array
    user_input = pd.DataFrame([[state, district, season, crop, area]], 
                            columns=['State_Name', 'District_Name', 'Season', 'Crop', 'Area'])
    
    # Transform input
    user_input_categorical = ohe.transform(user_input[categorical_cols])
    user_input_final = np.hstack((user_input_categorical, user_input.drop(categorical_cols, axis=1)))
    
    # Make prediction
    prediction = model.predict(user_input_final)[0]
    
    # Ensure prediction is non-negative
    prediction = max(0, prediction)
    
    print(f"{prediction:.2f}")

except Exception as e:
    print(f"Error: {str(e)}")
    sys.exit(1)