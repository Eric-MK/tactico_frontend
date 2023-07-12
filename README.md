# Football Recommender System

## Description

This repository contains a football recommender system built using machine learning. The frontend of the system is developed using Laravel framework, and it connects to the machine learning model through a Flask API. The model is hosted in a separate repository, which you will need to download in order to run the Laravel application and access the model.

## Installation and Setup

To set up the football recommender system, follow the steps below:

1. Clone this repository to your local machine.
2. Download the machine learning model repository from [Model Repository](https://github.com/KasuniB/Tactico/tree/main) and follow the steps listed there to run it
3. Install the required dependencies for the Laravel application by running the following commands:

```bash
composer install
```
## Usage

Once you have completed the installation and setup, you can use the football recommender system as follows:

1. Start the Flask API for the machine learning model by following the instructions provided in the model repository.

2. Launch the Laravel application by running the following command in the command line or terminal:

   ```bash
   php artisan serve
   ```
Create a user and log in to access the application.  
Specify the player type: 'Outfield players' or 'goalkeepers'  
Input the player in the format: 'First Name' 'Second Name'(Club)  
Specify the comparison as 'All Positions'  
Specify the league. Since the data is for the European top 5 leagues, choose 'All', 'La Liga', 'Premier League', 'Bundesliga', 'Serie A' or 'Ligue 1'  
Submit to get your recommendations

## Credits  
The development of this project was made possible by the following individuals:  

[Moses Kasuni](https://github.com/KasuniB)   
[Eric Mutunga](https://github.com/Eric-MK)    
We also extend our gratitude to the developers of the Laravel framework and the Python Flask library. 


## Contributing
Contributions to the Football Recommender System are welcome! If you have any ideas, improvements, or bug fixes, please open an issue or submit a pull request.

