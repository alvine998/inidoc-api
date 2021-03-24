<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Apotek;
use App\User;
use App\Ambulance;
use App\Category;
use App\ChatChannel;
use App\Product;
use App\ProductImage;
use App\Cart;
use App\Address;
use App\Transaction;
use App\TestLab;
use App\TestLabTransaction;
use App\Notification;
use App\Doctor;
use App\Hospital;
use App\HospitalPromise;
use App\SaldoApotek;
use App\SaldoProdia;
use App\SaldoDoctor;
use App\SaldoUser;
use App\Resep;

class ApiController extends Controller
{
    // Get
    public function getUsersById(Request $request, User $user) {
        $user = User::where('id_user', '=', $request->id_user)->first();
        return response($user);
    }
    
    public function getApotekById(Request $request) {
        $apotek = Apotek::where('id_apotek', '=', $request->id_apotek)->first();
        return response($apotek);
    }

    public function getCategory(Request $request, Category $category) {
        $category = Category::all();
        return response($category);
    }

    public function getProduct(Request $request, Product $product) {
        $product = Product::where('is_delete', '=', '0')
        ->where('id_apotek', '=', null)
        ->orderBy('id_product', 'desc')
        ->join('tb_user', 'tb_product.id_user', '=', 'tb_user.id_user')->get();
        return response($product);
    }
    
    public function getMyAddress(Request $request, Address $address) {
        $address = Address::where('id_user', '=', $request->id_user)
        ->orderBy('id_address', 'desc')
        ->first();
        return response($address);
    }

    public function getProductByUser(Request $request, Product $product) {
        $product = Product::where('tb_product.id_user', '=', $request->id_user)
        ->where('is_delete', '=', '0')
        ->join('tb_user', 'tb_product.id_user', '=', 'tb_user.id_user')
        ->get();
        if ($product -> isNotEmpty()) {
            return response()->json([
                'product' => $product,
                'success' => true,
                'message' => 'Data Tersedia'
            ]);
        } else {
            return response()->json([
                'product' => $product,
                'success' => false,
                'message' => 'Data Barang Tersedia'
            ]);
        }
    }
    
    public function getProductByApotek(Request $request, Product $product) {
        $product = Product::where('tb_product.id_apotek', '=', $request->id_apotek)
        ->where('is_delete', '=', '0')
        ->join('tb_apotek', 'tb_product.id_apotek', '=', 'tb_apotek.id_apotek')
        ->get();
        if ($product -> isNotEmpty()) {
            return response()->json([
                'product' => $product,
                'success' => true,
                'message' => 'Data Tersedia'
            ]);
        } else {
            return response()->json([
                'product' => $product,
                'success' => false,
                'message' => 'Data Barang Tersedia'
            ]);
        }
    }
    
    public function getResep(Request $request, Resep $resep) {
        $resep = Resep::where('tb_resep.id_user', '=', $request->id_user)
        ->join('tb_apotek', 'tb_resep.id_apotek', '=', 'tb_apotek.id_apotek')->get();
        if ($resep -> isNotEmpty()) {
            return response()->json([
                'resep' => $resep,
                'success' => true,
                'message' => 'Data Tersedia'
            ]);
        } else {
            return response()->json([
                'resep' => $resep,
                'success' => false,
                'message' => 'Data Barang Tersedia'
            ]);
        }
    }
    
    public function getResepByApotek(Request $request, Resep $resep) {
        $resep = Resep::where('tb_resep.id_apotek', '=', $request->id_apotek)
        ->join('tb_apotek', 'tb_resep.id_apotek', '=', 'tb_apotek.id_apotek')
        ->join('tb_user', 'tb_resep.id_user', '=', 'tb_user.id_user')->get();
        if ($resep -> isNotEmpty()) {
            return response()->json([
                'resep' => $resep,
                'success' => true,
                'message' => 'Data Tersedia'
            ]);
        } else {
            return response()->json([
                'resep' => $resep,
                'success' => false,
                'message' => 'Data Barang Tersedia'
            ]);
        }
    }
    
    public function getTransaction(Request $request, Transaction $transaction) {
        $check = Transaction::where('tb_transaction.id_buyer', '=', $request->id_buyer)
        ->orderByDesc('id_transaction')
        ->first();
        
        if ($check->id_seller == null) {
            $transaction = Transaction::where('tb_transaction.id_buyer', '=', $request->id_buyer)
            ->where('status_transaction', '=', $request->status_transaction)
            ->join('tb_apotek', 'tb_transaction.id_apotek', '=', 'tb_apotek.id_apotek')
            ->join('tb_product', 'tb_transaction.id_product', '=', 'tb_product.id_product')
            ->get();
        } else {
            $transaction = Transaction::where('tb_transaction.id_buyer', '=', $request->id_buyer)
            ->where('status_transaction', '=', $request->status_transaction)
            ->join('tb_user', 'tb_transaction.id_seller', '=', 'tb_user.id_user')
            ->join('tb_product', 'tb_transaction.id_product', '=', 'tb_product.id_product')
            ->get();
        }
        
        if ($transaction -> isNotEmpty()) {
            return response()->json([
                'transaction'   => $transaction,
                'success' => true,
                'message' => 'Data Transaction Tersedia'
            ]);
        } else {
            return response()->json([
                'transaction'   => $transaction,
                'success' => false,
                'message' => 'Data Transaction Tidak Tersedia'
            ]);
        }
    }
    
    public function getTransactionSeller(Request $request, Transaction $transaction) {
        $transaction = Transaction::where('tb_transaction.id_seller', '=', $request->id_seller)
        ->where('status_transaction', '=', $request->status_transaction)
        ->join('tb_user', 'tb_transaction.id_buyer', '=', 'tb_user.id_user')
        ->join('tb_product', 'tb_transaction.id_product', '=', 'tb_product.id_product')
        ->get();
        if ($transaction -> isNotEmpty()) {
            return response()->json([
                'transaction'   => $transaction,
                'success' => true,
                'message' => 'Data Transaction Tersedia'
            ]);
        } else {
            return response()->json([
                'transaction'   => $transaction,
                'success' => false,
                'message' => 'Data Transaction Tidak Tersedia'
            ]);
        }
    }
    
    public function getTransactionApotek(Request $request, Transaction $transaction) {
        $transaction = Transaction::where('tb_transaction.id_apotek', '=', $request->id_apotek)
        ->where('status_transaction', '=', $request->status_transaction)
        ->join('tb_user', 'tb_transaction.id_buyer', '=', 'tb_user.id_user')
        ->join('tb_product', 'tb_transaction.id_product', '=', 'tb_product.id_product')
        ->get();
        if ($transaction -> isNotEmpty()) {
            return response()->json([
                'transaction'   => $transaction,
                'success' => true,
                'message' => 'Data Transaction Tersedia'
            ]);
        } else {
            return response()->json([
                'transaction'   => $transaction,
                'success' => false,
                'message' => 'Data Transaction Tidak Tersedia'
            ]);
        }
    }


    public function getAllDoctor(Request $request, Doctor $doctor) {
        $doctor = Doctor::where('is_active', '=', '1')->get();
        if ($doctor -> isNotEmpty()) {
            return response()->json([
                'doctor'     => $doctor,
                'success'  => true,
                'message'  => 'Data tersedia'
            ]);   
        } else {
            return response()->json([
                'doctor'     => $doctor,
                'success'  => false,
                'message'  => 'Data tidak tersedia'
            ]);   
        }
    }
    
    public function getDoctorSpesialis(Request $request, Doctor $doctor) {
        $doctor = Doctor::where('is_active', '=', '1')
        ->where('spesialis', 'like', '%' . $request->spesialis . '%')
        ->get();
        if ($doctor -> isNotEmpty()) {
            return response()->json([
                'doctor'     => $doctor,
                'success'  => true,
                'message'  => 'Data tersedia'
            ]);   
        } else {
            return response()->json([
                'doctor'     => $doctor,
                'success'  => false,
                'message'  => 'Data tidak tersedia'
            ]);   
        }
    }
    
    public function getRecommendDoctor(Request $request, Doctor $doctor) {
        $doctor = Doctor::where('is_active', '=', '1')->limit(6)->get();
        return response($doctor);
    }
    
    
    public function getAllPasien(Request $request, User $user) {
        $user = User::all();
        return response($user);
    }
    
    public function getAmbulance(Request $request) {
        $ambulance = Ambulance::where('tb_ambulance.is_active', '=', '1')
        ->join('tb_hospital', 'tb_ambulance.id_hospital', '=', 'tb_hospital.id_hospital')
        ->select('tb_ambulance.*', 'tb_hospital.name_hospital', 'tb_hospital.phone_number_hospital', 'tb_hospital.address_hospital', 'tb_hospital.longitude_hospital', 'tb_hospital.latitude_hospital')
        ->get();
        return response($ambulance);
    }
    
    public function getMyAmbulance(Request $request) {
        $ambulance = Ambulance::where('id_hospital', '=', $request->id_hospital)
        ->get();
        return response($ambulance);
    }
    
    public function getAllTestLab(Request $request) {
        $testlab = TestLab::where('is_active', '=', '1')->get();
        return response($testlab);
    }
    
    public function getAllApotek(Request $request) {
        $apotek = Apotek::all();
        return response($apotek);
    }
    
    public function getTestLab(Request $request) {
        $testlab = TestLab::where('is_active', '=', '1')
        ->where('id_test_lab', '=', $request->id_test_lab)->first();
        return response($testlab);
    }
    
    public function getNotification(Request $request) {
        $notif = Notification::where('id_receiver', '=', $request->id_receiver)->orderBy('id_notification', 'desc')->get();
        if ($notif -> isNotEmpty()) {
            return response()->json([
                'notification'     => $notif,
                'success'  => true,
                'message'  => 'Data tersedia'
            ]);   
        } else {
            return response()->json([
                'notification'     => $notif,
                'success'  => false,
                'message'  => 'Data tidak tersedia'
            ]);   
        }
    }
    
    public function getAllHospital(Request $request, Hospital $hospital) {
        $hospital = Hospital::where('is_active', '=', '1')->get();
        return response($hospital);
    }
    
    public function getMyHospital(Request $request, Hospital $hospital) {
        $hospital = Hospital::where('id_hospital', '=', $request->id_hospital)
        ->first();
        return response($hospital);
    }
    
    public function getHospitalPromise(Request $request, HospitalPromise $hospital) {
        $hospital = HospitalPromise::where('id_hospital', '=', $request->id_hospital)
        ->join('tb_user', 'tb_hospital_promise.id_user', '=', 'tb_user.id_user')
        ->get();
        if ($hospital -> isNotEmpty()) {
            return response()->json([
                'hospital'     => $hospital,
                'success'  => true,
                'message'  => 'Data tersedia'
            ]);   
        } else {
            return response()->json([
                'hospital'     => $hospital,
                'success'  => false,
                'message'  => 'Data tidak tersedia'
            ]);   
        }
    }
    
    public function getHospitalPromiseByUser(Request $request, HospitalPromise $schedule) {
        $schedule = HospitalPromise::where('tb_hospital_promise.id_user', '=', $request->id_user)
        ->join('tb_user', 'tb_hospital_promise.id_user', '=', 'tb_user.id_user')
        ->join('tb_hospital', 'tb_hospital_promise.id_hospital', '=', 'tb_hospital.id_hospital')
        ->orderBy('id_hospital_promise', 'desc')
        ->get();
        if ($schedule -> isNotEmpty()) {
            return response()->json([
                'schedule'     => $schedule,
                'success'  => true,
                'message'  => 'Data tersedia'
            ]);   
        } else {
            return response()->json([
                'schedule'     => $schedule,
                'success'  => false,
                'message'  => 'Data tidak tersedia'
            ]);   
        }
    }
    
    public function getChat(Request $request, ChatChannel $chat) {
        $chat = ChatChannel::where('id_user', '=', $request->id_user)
        ->where('id_doctor', '=', $request->id_doctor)->get();
        
        if ($chat -> isNotEmpty()) {
            $user = User::where('id_user', '=', $request->id_user)->first();
            $doctor = Doctor::where('id_doctor', '=', $request->id_doctor)->first();
            return response()->json([
                'chat'     => $chat,
                'user'     => $user,
                'doctor'   => $doctor,
                'success'  => true,
                'message'  => 'Data tersedia'
            ]);   
        } else {
            return response()->json([
                'success'  => false,
                'message'  => 'Data tidak tersedia'
            ]);
        }
    }
    
    public function getChatByUser(Request $request, ChatChannel $chat) {
        $chat = ChatChannel::where('id_user', '=', $request->id_user)
        ->join('tb_doctor', 'tb_chat_channel.id_doctor', '=', 'tb_doctor.id_doctor')
        ->select('tb_chat_channel.*', 'tb_doctor.name_doctor', 'tb_doctor.picture_profile_doctor', 'tb_doctor.doctor_user_firebase')
        ->orderBy('id_chat_channel', 'desc')
        ->get();
        
        if ($chat -> isNotEmpty()) {
            return response()->json([
                'chat'     => $chat,
                'success'  => true,
                'message'  => 'Data tersedia'
            ]);   
        } else {
            return response()->json([
                'success'  => false,
                'message'  => 'Data tidak tersedia'
            ]);
        }
    }
    
    public function getChatByDoctor(Request $request, ChatChannel $chat) {
        $chat = ChatChannel::where('id_doctor', '=', $request->id_doctor)
        ->join('tb_user', 'tb_chat_channel.id_user', '=', 'tb_user.id_user')
        ->select('tb_chat_channel.*', 'tb_user.first_name', 'tb_user.last_name','tb_user.picture_profile', 'tb_user.user_id_firebase')
        ->orderBy('id_chat_channel', 'desc')
        ->get();
        
        if ($chat -> isNotEmpty()) {
            return response()->json([
                'chat'     => $chat,
                'success'  => true,
                'message'  => 'Data tersedia'
            ]);   
        } else {
            return response()->json([
                'success'  => false,
                'message'  => 'Data tidak tersedia'
            ]);
        }
    }
    
    public function getMyCart(Request $request, Cart $cart) {
        $cart = Cart::where('tb_cart.id_user', '=', $request->id_user)
                          ->join('tb_user', 'tb_cart.id_user', '=', 'tb_user.id_user')
                          ->join('tb_product', 'tb_cart.id_product', '=', 'tb_product.id_product')
                          ->get();
        
        $countcart = Cart::where('tb_cart.id_user', '=', $request->id_user)->count();
         return response()->json([
            'cart'          => $cart,
            'countmycart'   => $countcart,
            'success'       => true,
            'message'       => 'Berikut Data Keranjang Anda'
        ]);
    }
    
    public function getProductImage(Request $request, ProductImage $product) {
        $product = ProductImage::where('id_product', '=', $request->id_product)->get();
        return response($product);
    }
    
    public function getTestLabUser(Request $request) {
        $teslab = TestLabTransaction::where('tb_test_lab_transaction.id_user', '=', $request->id_user)
        ->join('tb_test_lab', 'tb_test_lab_transaction.id_test_lab', '=', 'tb_test_lab.id_test_lab')
        ->join('tb_user', 'tb_test_lab_transaction.id_user', '=', 'tb_user.id_user')
        ->get();
        if ($teslab -> isNotEmpty()) {
            return response()->json([
                'testlab'     => $teslab,
                'success'  => true,
                'message'  => 'Data tersedia'
            ]);   
        } else {
            return response()->json([
                'teslab'     => $teslab,
                'success'  => false,
                'message'  => 'Data tidak tersedia'
            ]);   
        }
    }
    
    public function getTestLabTransaction(Request $request) {
        $teslab = DB::table('tb_test_lab_transaction')
        ->join('tb_test_lab', 'tb_test_lab_transaction.id_test_lab', '=', 'tb_test_lab.id_test_lab')
        ->join('tb_user', 'tb_test_lab_transaction.id_user', '=', 'tb_user.id_user')
        ->get();
        if ($teslab -> isNotEmpty()) {
            return response()->json([
                'testlab'     => $teslab,
                'success'  => true,
                'message'  => 'Data tersedia'
            ]);   
        } else {
            return response()->json([
                'teslab'     => $teslab,
                'success'  => false,
                'message'  => 'Data tidak tersedia'
            ]);   
        }
    }

    public function getDoctorById(Request $request) {
        $doctor = Doctor::where('id_doctor', '=', $request->id_doctor)->first();
        return response($doctor);
    }
    
    // Post
    public function registerUser(Request $request) {
        $users = new User();

        $users->first_name = $request->input('first_name');
        $users->last_name = $request->input('last_name');
        $users->phone_number = $request->input('phone_number');
        $users->email = $request->input('email');
        $users->fcm_token = $request->input('fcm_token');
        $users->user_id_firebase = $request->input('user_id_firebase');
        $users->saldo = $request->input('saldo');
        $photo = $request->input('picture_profile');
        $imageName = "profile"."_".time().".png";
        \File::put(public_path(). '/upload/profile/' . $imageName, base64_decode($photo));
        $users->picture_profile = $imageName;
        $users->save();

        return response()->json([
            'users'   => $users,
            'success' => true,
            'message' => 'Pendaftaran akun berhasil'
        ]);
    }
    
    public function registerDoctor(Request $request) {
        $doctor = new Doctor();

        $doctor->name_doctor = $request->input('name_doctor');
        $doctor->email_doctor = $request->input('email_doctor');
        $doctor->gender = $request->input('gender');
        $doctor->phone_number_doctor = $request->input('phone_number_doctor');
        $doctor->spesialis = $request->input('spesialis');
        $doctor->practice_place = $request->input('practice_place');
        $doctor->alumni = $request->input('alumni');
        $doctor->str_number = $request->input('str_number');
        $doctor->consultation_fee = $request->input('consultation_fee');
        $doctor->doctor_user_firebase = $request->input('doctor_user_firebase');
        $doctor->fcm_token_doctor = $request->input('fcm_token_doctor');
        $doctor->saldo_doctor = $request->input('saldo_doctor');
        $doctor->is_active = $request->input('is_active');
        $photo = $request->input('picture_profile_doctor');
        $imageName = "doctor"."_".time().".png";
        \File::put(public_path(). '/upload/doctor/' . $imageName, base64_decode($photo));
        $doctor->picture_profile_doctor = $imageName;
        $doctor->save();

        return response()->json([
            'doctor'   => $doctor,
            'success' => true,
            'message' => 'Pendaftaran akun dokter berhasil'
        ]);
    }
    
    public function registerHospital(Request $request) {
        $hospital = new Hospital();

        $hospital->description_hospital = $request->input('description_hospital');
        $hospital->name_hospital = $request->input('name_hospital');
        $hospital->address_hospital = $request->input('address_hospital');
        $hospital->longitude_hospital = $request->input('longitude_hospital');
        $hospital->latitude_hospital = $request->input('latitude_hospital');
        $hospital->city_hospital = $request->input('city_hospital');
        $hospital->postal_code_hospital = $request->input('postal_code_hospital');
        $hospital->phone_number = $request->input('phone_number');
        $hospital->phone_number_hospital = $request->input('phone_number_hospital');
        $hospital->email_hospital = $request->input('email_hospital');
        $hospital->npwp_hospital = $request->input('npwp_hospital');
        $hospital->is_active = $request->input('is_active');
        $hospital->treatment_hospital = $request->input('treatment_hospital');
        $photo = $request->input('picture_hospital');
        $imageName = "rumahsakit"."_".time().".png";
        \File::put(public_path(). '/upload/hospital/' . $imageName, base64_decode($photo));
        $hospital->picture_hospital = $imageName;
        $hospital->save();

        return response()->json([
            'hospital'   => $hospital,
            'success' => true,
            'message' => 'Pendaftaran rumah sakit berhasil'
        ]);
    }
    
    public function registerApotek(Request $request) {
        $apotek = new Apotek();

        $apotek->nama_apotek = $request->input('nama_apotek');
        $apotek->alamat_apotek = $request->input('alamat_apotek');
        $apotek->longitude_apotek = $request->input('longitude_apotek');
        $apotek->latitude_apotek = $request->input('latitude_apotek');
        $apotek->nomor_izin_apotek = $request->input('nomor_izin_apotek');
        $apotek->penanggung_jawab = $request->input('penanggung_jawab');
        $apotek->tanggal_berdiri = $request->input('tanggal_berdiri');
        $apotek->no_telepon = $request->input('no_telepon');
        $apotek->no_handphone = $request->input('no_handphone');
        $apotek->email_apotek = $request->input('email_apotek');
        $apotek->id_provinsi = $request->input('id_provinsi');
        $apotek->id_kota = $request->input('id_kota');
        $apotek->id_origin = $request->input('id_origin');
        $apotek->id_kecamatan = $request->input('id_kecamatan');
        $apotek->id_kelurahan = $request->input('id_kelurahan');
        $apotek->saldo_apotek = 0;
        $photo = $request->input('logo_apotek');
        $imageName = "apotek"."_".time().".png";
        \File::put(public_path(). '/upload/apotek/' . $imageName, base64_decode($photo));
        $apotek->logo_apotek = $imageName;
        $apotek->save();
        
        $idApotek = Apotek::orderByDesc('id_apotek')->first();

        return response()->json([
            'success' => true,
            'message' => $idApotek->id_apotek
        ]);
    }
    
    public function postCart(Request $request) {
        $cart = new Cart();

        $cart->id_product = $request->input('id_product');
        $cart->id_user = $request->input('id_user');
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Ditambahkan Ke Kranjang'
        ]);
    }
    
    public function postAmbulance(Request $request) {
        $ambulance = new Ambulance();

        $ambulance->id_hospital = $request->input('id_hospital');
        $ambulance->type_car = $request->input('type_car');
        $ambulance->number_plate = $request->input('number_plate');
        $ambulance->is_active = $request->input('is_active');
        $ambulance->phone_number_ambulance = $request->input('phone_number_ambulance');
        $photo = $request->input('picture_ambulance');
        $imageName = "ambulance"."_".time().".png";
        \File::put(public_path(). '/upload/ambulance/' . $imageName, base64_decode($photo));
        $ambulance->picture_ambulance = $imageName;
        $ambulance->save();

        return response()->json([
            'success' => true,
            'message' => 'Ambulance berhasil di tambahkan'
        ]);
    }
    
    public function postChat(Request $request, ChatChannel $chat) {
        $chat = ChatChannel::where('id_user', '=', $request->id_user)
        ->where('id_doctor', '=', $request->id_doctor)->first();
        
        if ($chat != null) {
            $chatChannel = ChatChannel::where('id_user', '=', $request->id_user)
            ->where('id_doctor', '=', $request->id_doctor)
            ->update(
                ['last_chat' => $request->input('last_chat'),
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Pesan terkirim!'
                ]);
        } else {
            $chatChannel = new ChatChannel();
            $chatChannel->id_user = $request->id_user;
            $chatChannel->id_doctor = $request->id_doctor;
            $chatChannel->last_chat = $request->input('last_chat');
            $chatChannel->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil Membuat Chat Channel!'
            ]);
        }
    }

    public function loginByPhone(Request $request, User $users) {
        $users = User::where('phone_number', '=', $request->input($users->phone_number))->first();
        if ($users != null) {
            return response()->json([
                'users'   => $users,
                'success' => true,
                'message' => 'Data Tersedia'
            ]);
        } else {
            return response()->json([
                'users'   => $users,
                'success' => false,
                'message' => 'Data Tidak Tersedia!'
            ]);
        }
    }
    
    public function loginDoctorByPhone(Request $request, Doctor $doctor) {
        $doctor = Doctor::where('phone_number_doctor', '=', $request->input($doctor->phone_number_doctor))->first();
        if ($doctor != null) {
            return response()->json([
                'doctor'   => $doctor,
                'success' => true,
                'message' => 'Data Tersedia'
            ]);
        } else {
            return response()->json([
                'doctor'   => $doctor,
                'success' => false,
                'message' => 'Data Tidak Tersedia!'
            ]);
        }
    }
    
    public function loginRSByPhone(Request $request, Hospital $hospital) {
        $hospital = Hospital::where('phone_number', '=', $request->input($hospital->phone_number))
        ->orWhere('phone_number_hospital', '=', $request->input($hospital->phone_number_hospital))
        ->first();
        if ($hospital != null) {
            return response()->json([
                'hospital'   => $hospital,
                'success'      => true,
                'message'      => 'Data Tersedia'
            ]);
        } else {
            return response()->json([
                'hospital'   => $hospital,
                'success'      => false,
                'message'      => 'Data Tidak Tersedia!'
            ]);
        }
    }
    
    public function loginApotekByPhone(Request $request, Apotek $apotek) {
        $apotek = Apotek::where('no_telepon', '=', $request->input($apotek->no_telepon))
        ->orWhere('no_handphone', '=', $request->input($apotek->no_handphone))
        ->first();
        if ($apotek != null) {
            return response()->json([
                'success'      => true,
                'message'      => $apotek->id_apotek
            ]);
        } else {
            return response()->json([
                'success'      => false,
                'message'      => 'Data Tidak Tersedia!'
            ]);
        }
    }

    public function postProduct(Request $request) {
        $product = new Product();

        $product->id_user = $request->input('id_user');
        $product->id_apotek = $request->input('id_apotek');
        $product->name_product = $request->input('name_product');
        $product->description_product = $request->input('description_product');
        $product->price_product = $request->input('price_product');
        $product->stock = $request->input('stock');
        $product->brand = $request->input('brand');
        $product->garansi = $request->input('garansi');
        $product->unit = $request->input('unit');
        $product->asal_produk = $request->input('asal_produk');
        $product->produk_khusus = $request->input('produk_khusus');
        $product->berat = $request->input('berat');
        $product->panjang = $request->input('panjang');
        $product->lebar = $request->input('lebar');
        $product->tinggi = $request->input('tinggi');
        $product->is_delete = $request->input('is_delete');
        $photo = $request->input('cover_product');
        $imageName = "product_image"."_".time().".png";
        \File::put(public_path(). '/upload/productimage/' . $imageName, base64_decode($photo));
        $product->cover_product = $imageName;
        $product->save();

        $productOrderById = Product::orderBy('id_product', 'desc')->first();
        $productId = $productOrderById->id_product;

        return response()->json([
            'productId' => $productId,
            'success' => true,
            'message' => 'Produk Berhasil Ditambahkan'
        ]);
    }

    public function postProductImage(Request $request) {
        $productImage = new ProductImage();
        $codePhoto = Str::random(5);
        $productImage->id_product = $request->input('id_product');
        $photo = $request->input('product_image');
        $imageName = "product_image"."_"."$codePhoto"."_".time().".png";
        \File::put(public_path(). '/upload/productimage/' . $imageName, base64_decode($photo));
        $productImage->product_image = $imageName;
        $productImage->save();

        return response()->json([
            'success' => true,
            'message' => 'Gambar Product Berhasil Ditambahkan'
        ]);
    }
    
    public function postAddress(Request $request) {
        $address = new Address();

        $address->id_user = $request->input('id_user');
        $address->id_provinsi = $request->input('id_provinsi');
        $address->id_kota = $request->input('id_kota');
        $address->id_kecamatan = $request->input('id_kecamatan');
        $address->type = $request->input('type');
        $address->address = $request->input('address');
        $address->postal_code = $request->input('postal_code');
        $address->longitude = $request->input('longitude');
        $address->latitude = $request->input('latitude');
        $address->save();

        return response()->json([
            'success' => true,
            'message' => 'Alamat Berhasil Ditambahkan'
        ]);
    }
    
    public function postTransaction(Request $request) {
        $transaction = new Transaction();

        $transaction->status_transaction = $request->input('status_transaction');
        $transaction->id_buyer = $request->input('id_buyer');
        $transaction->id_seller = $request->input('id_seller');
        $transaction->id_apotek = $request->input('id_apotek');
        $transaction->id_product = $request->input('id_product');
        $transaction->metode_bayar = $request->input('metode_bayar');
        $transaction->sub_total_product = $request->input('sub_total_product');
        $transaction->sub_total_pengiriman = $request->input('sub_total_pengiriman');
        $transaction->sub_total_pembayaran = $request->input('sub_total_pembayaran');
        $transaction->jasa_kirim = $request->input('jasa_kirim');
        $transaction->save();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi Berhasil'
        ]);
    }
    
    public function postPromiseHospital(Request $request) {
        $promise = new HospitalPromise();

        $promise->id_hospital = $request->input('id_hospital');
        $promise->id_user = $request->input('id_user');
        $promise->date_promise = $request->input('date_promise');
        $promise->note_promise = $request->input('note_promise');
        $promise->save();

        return response()->json([
            'success' => true,
            'message' => 'Janji Berhasil Dibuat'
        ]);
    }
    
    public function postTestLab(Request $request) {
        $lab = new TestLab();

        // $lab->id_hospital = $request->input('id_hospital');
        $lab->name_test_lab = $request->input('name_test_lab');
        $lab->description_test_lab = $request->input('description_test_lab');
        $lab->price_test_lab = $request->input('price_test_lab');
        $lab->jenis_pemeriksaan = $request->input('jenis_pemeriksaan');
        $lab->persiapan_test_lab = $request->input('persiapan_test_lab');
        $lab->sampel_test_lab = $request->input('sampel_test_lab');
        $lab->is_active = 1;
        $photo = $request->input('picture_test_lab');
        $imageName = "testlab"."_".time().".png";
        \File::put(public_path(). '/upload/testlab/' . $imageName, base64_decode($photo));
        $lab->picture_test_lab = $imageName;
        $lab->save();

        $testLab = TestLab::orderByDesc('id_test_lab')->first();
        
        return response()->json([
            'success' => true,
            'message' => 'Test Lab Berhasil Dibuat',
            'imageName' => $imageName,
            'id_test_lab' => $testLab->id_test_lab
        ]);
    }
    
    public function postTestLabTransaction(Request $request) {
        $lab = new TestLabTransaction();
        
        $lab->id_test_lab = $request->input('id_test_lab');
        $lab->id_user = $request->input('id_user');
        $lab->date_test_lab = $request->input('date_test_lab');
        $lab->time_test_lab = $request->input('time_test_lab');
        $lab->save();

        return response()->json([
            'success' => true,
            'message' => 'Test Lab Berhasil Dibuat',
        ]);
    }
    
    public function postResep(Request $request) {
        $resep = new Resep();

        $resep->id_user = $request->input('id_user');
        $resep->id_apotek = $request->input('id_apotek');
        $resep->status = $request->input('status');
        $photo = $request->input('image_resep');
        $imageName = "resep"."_".time().".png";
        \File::put(public_path(). '/upload/resep/' . $imageName, base64_decode($photo));
        $resep->image_resep = $imageName;
        $resep->save();

        return response()->json([
            'success' => true,
            'message' => 'Resep berhasil diupload'
        ]);
    }
    
    public function postNotification(Request $request) {
        $notification = new Notification();

        $notification->id_receiver = $request->input('id_receiver');
        $notification->title = $request->input('title');
        $notification->message = $request->input('message');
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Notification Berhasil Dikirim'
        ]);
    }
    
    public function postSaldoUser(Request $request) {
        $saldouser = new SaldoUser();

        $saldouser->id_user = $request->input('id_user');
        $saldouser->transaction_name = $request->input('transaction_name');
        $saldouser->transaction_amount = $request->input('transaction_amount');
        
        $userData = User::where('id_user', '=', $request->id_user)->first();
        $lastSaldo = $userData -> saldo == null ?
        0 : $userData -> saldo;
        $amountSaldo = $request->input('transaction_amount');
        $user = User::where('id_user', '=', $request->id_user)
        ->update([
            'saldo' => $lastSaldo + $amountSaldo,
        ]);
        
        $saldouser->save();

        return response()->json([
            'success' => true,
            'message' => 'Saldo Sudah Terupdate'
        ]);
    }

    public function postSaldoDoctor(Request $request) {
        $saldodoctor = new SaldoDoctor();

        $saldodoctor->id_doctor = $request->input('id_doctor');
        $saldodoctor->transaction_name = $request->input('transaction_name');
        $saldodoctor->transaction_amount = $request->input('transaction_amount');
        
        $userData = Doctor::where('id_doctor', '=', $request->id_doctor)->first();
        $lastSaldo = $userData -> saldo_doctor == null ?
        0 : $userData -> saldo_doctor;
        $amountSaldo = $request->input('transaction_amount');
        $doctor = Doctor::where('id_doctor', $request->id_doctor)
        ->update([
            'saldo_doctor' => $lastSaldo + $amountSaldo,
        ]);
        
        $saldodoctor->save();

        return response()->json([
            'success' => true,
            'message' => 'Saldo Sudah Terupdate'
        ]);
    }
    
    public function postSaldoApotek(Request $request) {
        $saldo = new SaldoApotek();

        $saldo->id_apotek = $request->input('id_apotek');
        $saldo->transaction_name = $request->input('transaction_name');
        $saldo->transaction_amount = $request->input('transaction_amount');
        
        $userData = Apotek::where('id_apotek', '=', $request->id_apotek)->first();
        $lastSaldo = $userData -> saldo_apotek == null ?
        0 : $userData -> saldo_apotek;
        $amountSaldo = $request->input('transaction_amount');
        $saldoApotek = Apotek::where('id_apotek', $request->id_apotek)
        ->update([
            'saldo_apotek' => $lastSaldo + $amountSaldo,
        ]);
        
        $saldo->save();

        return response()->json([
            'success' => true,
            'message' => 'Saldo Sudah Terupdate'
        ]);
    }
    
    public function postSaldoProdia(Request $request) {
        $saldo = new SaldoProdia();

        $saldo->id_user = $request->input('id_user');
        $saldo->transaction_name = $request->input('transaction_name');
        $saldo->transaction_amount = $request->input('transaction_amount');
        
        $saldo->save();

        return response()->json([
            'success' => true,
            'message' => 'Saldo Sudah Terupdate'
        ]);
    }
    
    // PUT
    
    public function putAddress(Request $request, Address $address) {
        $address = Address::where('id_address', $request->id_address)
        ->update([
            'id_user' => $request->input('id_user'),
            'id_provinsi' => $request->input('id_provinsi'),
            'id_kota' => $request->input('id_kota'),
            'id_kecamatan' => $request->input('id_kecamatan'),
            'type' => $request->input('type'),
            'address' => $request->input('address'),
            'postal_code' => $request->input('postal_code'),
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude'),
            ]);
        return response()->json([
            'success' => true,
            'message' => 'Alamat Berhasil Diupdate'
        ]);
    }

    public function updateProfil(Request $request, User $users)
    {
        $users = User::find($request->id);

        // Hapus Foto Lama
        $picture_profil_old = $users->picture_profil;

        $users->full_name = $request->input('full_name');
        $users->phone_number = $request->input('phone_number');
        $users->email = $request->input('email');
        $users->password = $request->input('password');
        $photo = $request->input('picture_profil');

        if ($photo != null) {
            \File::delete(public_path(). '/upload/profil/' . $picture_profil_old);

            $name = $users->full_name;
            $imageName = "profil_"."$name"."_".time().".png";
            \File::put(public_path(). '/upload/profil/' . $imageName, base64_decode($photo));
            $users->picture_profil = $imageName;
        } else {
            $users->picture_profil = $picture_profil_old;
        }

        $users->save();
        return response()->json([
            'users'   => $users,
            'success' => true,
            'message' => 'Update Profil Berhasil'
        ]);
    }
    
    public function updateAmbulance(Request $request, Ambulance $ambulance)
    {
        $photo = $request->input('picture_ambulance');
        if ($photo != null) {
            $imageName = "ambulance_".time().".png";
            \File::put(public_path(). '/upload/ambulance/'. $imageName, base64_decode($photo));
            $ambulance->picture_ambulance = $imageName;
        }
        
        $ambulance = Ambulance::where('id_ambulance', $request->id_ambulance)
        ->update([
            'type_car' => $request->input('type_car'),
            'number_plate' => $request->input('number_plate'),
            'phone_number_ambulance' => $request->input('phone_number_ambulance'),
            'is_active' => $request->input('is_active'),
            'picture_ambulance' => $imageName,
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Update Data Ambulance Berhasil'
        ]);
    }
    
    public function updateProduct(Request $request, Product $product) {
        $product = Product::where('id_product', $request->id_product)
        ->update([
            'name_product' => $request->input('name_product'),
            'description_product' => $request->input('description_product'),
            'price_product' => $request->input('price_product'),
            'stock' => $request->input('stock'),
            'brand' => $request->input('brand'),
            'garansi' => $request->input('garansi'),
            'unit' => $request->input('unit'),
            'asal_produk' => $request->input('asal_produk'),
            'produk_khusus' => $request->input('produk_khusus'),
            'berat' => $request->input('berat'),
            'panjang' => $request->input('panjang'),
            'lebar' => $request->input('lebar'),
            'tinggi' => $request->input('tinggi'),
            ]);
        return response()->json([
            'success' => true,
            'message' => 'Produk Berhasil Diupdate'
        ]);
    }
    
    public function deleteProduct(Request $request, Product $product) {
        $product = Product::where('id_product', $request->id_product)
        ->update([
            'is_delete' => $request->input('is_delete'),
            ]);
        return response()->json([
            'success' => true,
            'message' => 'Produk Berhasil Dihapus'
        ]);
    }

    public function putTransaction(Request $request, Transaction $transaction) {
        $transaction = Transaction::where('id_transaction', $request->id_transaction) 
        ->update([
            'status_transaction' => $request->input('status_transaction'),
            'resi' => $request->input('resi'),
            ]);
        return response()->json([
            'success' => true,
            'message' => 'Status Transaksi Berhasil Diupdate'
        ]);
    }
    
    public function putResep(Request $request) {
        $resep = Resep::where('id_resep', $request->id_resep) 
        ->update([
            'status' => $request->input('status'),
            'harga' => $request->input('harga'),
            ]);
        return response()->json([
            'success' => true,
            'message' => 'Status Resep Berhasil Diupdate'
        ]);
    }

    public function putDoctor(Request $request) {
        $doctor = Doctor::where('id_doctor', $request->id_doctor) 
        ->update([
            'name_doctor' => $request->input('name_doctor'),
            'email_doctor' => $request->input('email_doctor'),
            'gender' => $request->input('gender'),
            'phone_number_doctor' => $request->input('phone_number_doctor'),
            'spesialis' => $request->input('spesialis'),
            'practice_place' => $request->input('practice_place'),
            'alumni' => $request->input('alumni'),
            'str_number' => $request->input('str_number'),
            'consultation_fee' => $request->input('consultation_fee'),
            'is_active' => $request->input('is_active'),
            ]);
        return response()->json([
            'success' => true,
            'message' => 'Update Profile Berhasil'
        ]);
    }

    public function putImageDoctor(Request $request) {
        $photo = $request->input('picture_profile_doctor');
        $imageName = "doctor"."_".time().".png";
        \File::put(public_path(). '/upload/doctor/' . $imageName, base64_decode($photo));
        $doctor = Doctor::where('id_doctor', $request->id_doctor)
        ->update([
            'picture_profile_doctor' => $imageName,
            ]);
        return response()->json([
            'success' => true,
            'message' => 'Update Foto Profile Berhasil'
        ]);
    }


    public function putFcmDoctor(Request $request) {
        $doctor = Doctor::where('id_doctor', $request->id_doctor)
        ->update([
            'fcm_token_doctor' => $request->input('fcm_token_doctor'),
            ]);
        return response()->json([
            'success' => true,
            'message' => 'Update FCM Token Dokter Berhasil'
        ]);
    }
    
    public function putTestlab(Request $request, Transaction $transaction) {
        $photo = $request->input('picture_test_lab');
        $imageName = "testlab"."_".time().".png";
        \File::put(public_path(). '/upload/testlab/' . $imageName, base64_decode($photo));
        
        $lab = TestLab::where('id_test_lab', $request->id_test_lab) 
        ->update([
            'name_test_lab' => $request->input('name_test_lab'),
            'description_test_lab' => $request->input('description_test_lab'),
            'price_test_lab' => $request->input('price_test_lab'),
            'jenis_pemeriksaan' => $request->input('jenis_pemeriksaan'),
            'persiapan_test_lab' => $request->input('persiapan_test_lab'),
            'sampel_test_lab' => $request->input('sampel_test_lab'),
            'is_active' => $request->input('is_active'),
            'picture_test_lab' => $imageName,
            ]);
        return response()->json([
            'success' => true,
            'message' => 'Test Lab Berhasil Diupdate'
        ]);
    }
    // DELETE
    
    public function deleteCart(Request $request, Cart $cart) {
        $cart = Cart::where('id_cart', '=', $request->id_cart)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Cart berhasil dihapus'
        ]);
    }
    
    public function deleteTestLab(Request $request, Cart $cart) {
        $lab = TestLab::where('id_test_lab', '=', $request->id_test_lab)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Testlab berhasil dihapus'
        ]);
    }
}
