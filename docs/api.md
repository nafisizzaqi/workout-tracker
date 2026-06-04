##Workout Tracker
- Apk yang bertujuan untuk membuat sebuah jadwal workout
- User bisa membuat latihan harian atau beberapa latihan
- User bisa melihat cek total workout, total latihan
- User juga bisa track workout yang masih pending, done, missed di Report

##Route
- Auth
    - login dan register /login dan /register hanya bisa diakses oleh user yang belum login dan register
    - logout bisa digunakan untuk logout /logout

- Exercise List /exercise-list akan mengambil seluruh exercise di database

- Workout untuk show, update, destroy di lindungi supaya hanya user lain tidak bisa mengakses 3 method itu
    - index mengambil workout yang dimiliki user yang sedang login yang statusnya pending, dan di urutkan berdasarkan tanggal 
    - store membuat data workout yang sekaligus membuat user exercise juga, dan set schedule dengan format Y-m-d H:i:s
    - show menampilkan 1 detail workout beserta user exercisenya yang dibuat sekaligus tadi
    - update update workout berserta user exercisenya, update schedule, serta dapat memberi comment untuk workoutnya
    - destroy menghapus workout beserta user exercise nya

- User Exercise untuk show, update, delete juga di lindungi policy
    - store membuat latihan user dengan weight, set, rep yang nempel dengan 1 sesi workout
    - show menampilkan detail dari user exercise
    - update mengupdate user exercise weight, set, rep
    - destroy menghapus user exercise
    
- Report menampilkan statistik berdasarkan rentang tanggal start dan end date, akan menampilkan 
    - total_workouts workout total diantara rentang tanggal yang di set
    - workout_missed workout yang statusnya missed
    - workout done workout yang statusnya done
    - total exercise total latihan di semua workout
    - exercise done latihan yang sudah selesai
    - sets_done menghitung total sets
    - reps_done menghitung total reps

##Test in POSTMAN
URL = http://localhost:8000/api
#Auth
    REGISTER
    - url : /register
    - Method POST
    - Header : Accept value -> application/json
    - raw-data : {
        "name": "User",
        "email": "user@gmail.com" ,
        "password": "password123",
        "password_confirmation": "password123"
    }
    
    LOGIN
    - url : /login
    - Method POST
    - Header : Accept value -> application/json
    - raw : {
        "email": "user@gmail.com",
        "password": "password123"
    }

    LOGOUT
    - url : /logout
    - Method POST
    - Header : Accept value -> application/json
    - Autorization type Bearear token : token yang didapatkan dari login

#Workout
    Authorization type bearer token dari login token
    INDEX
    - url : /workout
    - Mehtod GET
    - Header : Accept value -> application/json
    STORE
    - url : /workout
    - Method POST
    - Header : Accept value -> application/json
    - raw : {
        "name": "Shoulder",
        "status": "pending",
        "schedule": "2026-06-01 08:00:00",
        "user_exercises": [
            {
                "exercise_id": 1,
                "description": "A basic bodyweight exercise that targets the chest, shoulders, and triceps.",
                "kilograms": 20,
                "set_count": 3,
                "rep_count": 15
            }
        ]
    }
    DETAIL
    - url : /workout/{id}
    - Method GET
    - Header : Accept value -> application/json
    UPDATE
    - url : /workout/{id}
    - Method PUT
    - Header : Accept value -> application/json
    - raw : {
        "name": "Shoulder",
        "schedule": "2026-06-10 10:00:00",
        "status": "done",
        "comment": "I've start in 10 AM to go gym",
        "user_exercises": [
            {
                "id": 4,
                "description": "A basic bodyweight exercise that targets the chest, shoulders, and triceps.",
                "exercise_id": 1,
                "kilograms": 20,
                "set_count": 3,
                "rep_count": 15
            }
        ]
    }
    DELETE
    - url : /workout/{id}
    - Method DELETE
    - Header : Accept value -> application/json

#UserExercise
    Authorization type bearer token dari login token
    STORE
    - url : /user-exercise
    - Method POST
    - Header : Accept value -> application/json
    - raw : {
        "workout_id": 2,
        "exercise_id": 5,
        "description": "Lunges 20kg",
        "kilograms": 20,
        "set_count": 3,
        "rep_count": 12
    }
    DETAIL
    - url : /user-exercise/{id}
    - Method GET
    - Header : Accept value -> application/json
    UPDATE
    - url : /user-exercise/{id}
    - Method PUT
    - Header : Accept value -> application/json
    - raw : {
        "description": "Latihan",
        "kilograms": 10,
        "set_count": 2,
        "rep_count": 8
    }
    DELETE
    - url : /user-exercise/{id}
    - Method DELETE
    - Header : Accept value -> application/json

#Exercise
    Authorization type bearer token dari login token
    INDEX
    - url : /exercise-list
    - Method GET
    - Header : Accept value -> application/json

#Report
    Authorization type bearer token dari login token
    INDEX
    - url : /report?params
    - Method GET
    - Header : Accept value -> application/json
    - params : start_date : 2026-06-01
               end_date : 2026-06-31