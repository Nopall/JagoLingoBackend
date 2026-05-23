<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\User;
use App\Models\FuelType;
use App\Models\History;
use App\Models\Earning;
use App\Models\Cost;
use App\Models\Route;
use App\Models\Charging;
use App\Models\Service;
use App\Models\NoteType;
use App\Models\Commodity;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Leaflet;
use App\Models\Notification;
use App\Models\SurfaceArea;
use App\Models\CommodityItem;
use App\Models\CommodityPrice;
use App\Models\ChatRoom;
use App\Models\ChatDetail;
use App\Models\News;
use App\Models\Pestisida;
use App\Models\Budidaya;
use App\Models\Pembiayaan;
use App\Models\PlanPlant;
use App\Models\Kecamatan;

use Validator;
use App\Http\Resources\CarResource;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
   
class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listCar(): JsonResponse
    {
        $user = auth()->user();

        $cars = Car::with('carBrand', 'fuelType', 'fuelTypeSecondary')->where('user_id', $user->id)->get();
    
        return $this->sendResponse(CarResource::collection($cars), 'Car retrieved successfully.');
    }

    public function listCarBrand(): JsonResponse
    {
        $user = auth()->user();

        $cars = CarBrand::all();
    
        return $this->sendResponse(CarResource::collection($cars), 'Car retrieved successfully.');
    }

    public function listFuelType(): JsonResponse
    {
        $user = auth()->user();

        $fuel = FuelType::all();
    
        return $this->sendResponse(CarResource::collection($fuel), 'FuelType retrieved successfully.');
    }
    
    public function listMenu(): JsonResponse
    {
        $user = auth()->user();

        $menu = NoteType::all();
    
        return $this->sendResponse(CarResource::collection($menu), 'Menu retrieved successfully.');
    }

    public function listCommodity(): JsonResponse
    {
        $menu = Commodity::all();
    
        return $this->sendResponse(CarResource::collection($menu), 'Commodity retrieved successfully.');
    }

    public function listLeaflet(): JsonResponse
    {
        $menu = Leaflet::all();
    
        return $this->sendResponse(CarResource::collection($menu), 'Leaflet retrieved successfully.');
    }

    public function listProduct(Request $request): JsonResponse
    {
        $commodityId = $request->input('commodity_id');

        // Jika 'commodity_id' tersedia, maka saring produk berdasarkan 'commodity_id'l
        if ($commodityId) {
            $product = Product::with('images')->where('commodity_id', $commodityId)->get();
        } else {
            // Jika 'commodity_id' tidak tersedia, maka ambil semua produk
            $product = Product::with('images')->get();
        }

        return $this->sendResponse(CarResource::collection($product), 'Product retrieved successfully.');
    }

    public function detailProduct($id): JsonResponse
    {
        $car = Product::with('images')->where('id', $id)->first();
  
        if (is_null($car)) {
            return $this->sendError('Product not found.');
        }
   
        return $this->sendResponse(new CarResource($car), 'Product retrieved successfully.');
    }

    public function listBanner(): JsonResponse
    {
        $menu = Banner::all();
    
        return $this->sendResponse(CarResource::collection($menu), 'Commodity retrieved successfully.');
    }
    
    public function listPestisida(): JsonResponse
    {
        $menu = Pestisida::all();
    
        return $this->sendResponse(CarResource::collection($menu), 'Pestisida retrieved successfully.');
    }
    
    public function listPembiayaan(): JsonResponse
    {
        $menu = Pembiayaan::all();
    
        return $this->sendResponse(CarResource::collection($menu), 'Pembiayaan retrieved successfully.');
    }
    
    public function budidayaHorti(): JsonResponse
    {
        $menu = Budidaya::all();
    
        return $this->sendResponse(CarResource::collection($menu), 'Budidaya Horti retrieved successfully.');
    }

    public function listNotification(): JsonResponse
    {
        $menu = Notification::all();
    
        return $this->sendResponse(CarResource::collection($menu), 'Commodity retrieved successfully.');
    }

    public function listSurfaceArea(Request $request): JsonResponse
    {
        $yearMonth = $request->input('month');
        list($year, $month) = explode('-', $yearMonth);
        
        // Ambil semua data dari tabel surface_area beserta relasinya
        $surfaceAreas = SurfaceArea::with(['commodityItem', 'subdistrict'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();
        
        // Kelompokkan data berdasarkan commodity_item_id
        $groupedData = $surfaceAreas->groupBy('commodity_item_id');
        
        // Inisialisasi koleksi kosong untuk hasil akhir
        $result = collect();
        
        // Iterasi melalui setiap komoditas yang mungkin
        CommodityItem::all()->each(function ($commodityItem) use ($groupedData, &$result) {
            // Ambil nama komoditas
            $commodityName = $commodityItem->name;
            
            // Ambil data terkait atau buat array kosong jika tidak ada data yang terkait
            $commodityData = $groupedData->get($commodityItem->id, collect());
        
            // Tambahkan komoditas beserta datanya ke dalam hasil
            $result->push([
                'commodity' => $commodityName,
                'data' => $commodityData
            ]);
        });
        
        return response()->json([
            'status' => 'success',
            'message' => 'Product retrieved successfully.',
            'month' => $yearMonth,
            'data' => $result
        ]);


    }

    public function listCommodityPrice(Request $request): JsonResponse
    {
        $bulan = Carbon::parse($request->input('date', now()))->month;
        
        // Mendapatkan semua data harga komoditas untuk bulan tertentu
        $hargaKomoditas = CommodityPrice::with('commodityItem')->whereMonth('date_input', $bulan)->orderBy('date_input')->get();

        // Struktur data respons
        $response = [
            'status' => 'success',
            'message' => 'Data harga komoditas berhasil diambil untuk bulan ' . $bulan,
            'data' => []
        ];

        // Membuat struktur data respons sesuai dengan kebutuhan
        foreach ($hargaKomoditas->groupBy('date_input') as $tanggal => $dataHariIni) {
            $dataHarga = [];

            // Membuat struktur data harga komoditas untuk setiap tanggal
            foreach ($dataHariIni as $harga) {
                $dataHarga[] = [
                    'commodity_name' => $harga->commodityItem->name,
                    'farmer_price' => $harga->farmer_price,
                    'seller_price' => $harga->seller_price
                ];
            }

            // Menambahkan data harga komoditas untuk tanggal ini ke dalam respons
            $response['data'][] = [
                'tanggal' => $tanggal,
                'commodity_price' => $dataHarga
            ];
        }

        return response()->json($response);
    }
    
    public function listRoom()
    {
        $user = auth()->user();
        $chatRooms = ChatRoom::with('admin')->where('user_id', $user->id)->get();

        // Periksa apakah $chatRooms kosong
        if ($chatRooms->isEmpty()) {
            return $this->sendResponse(CarResource::collection($chatRooms), 'Chat Room retrieved successfully.');
        }
        
        $formattedChatRooms = $chatRooms->map(function ($chatRoom) {
            $lastMessageDate = $chatRoom->getLastMessageDate();
            $createdAt = null;
        
            if ($lastMessageDate !== null) {
                $timeAgoString = $this->timeAgoString($lastMessageDate);
                
                if ($timeAgoString !== null) {
                    $createdAt = $timeAgoString->format('Y-m-d H:i:s');
                }
            }
        
            return [
                'id' => $chatRoom->id,
                'opposite' => $chatRoom->admin,
                'name' => $chatRoom->getLastSender(),
                'type' => $chatRoom->getLastTypeMessage(),
                'last_message' => $chatRoom->getLastMessage(),
                'created_at' => $createdAt
            ];
        });
        
        // Mengembalikan data dalam format JSON
        return $this->sendResponse(CarResource::collection($formattedChatRooms), 'Chat Room retrieved successfully.');

    }

    public function listPlanPlant(Request $request): JsonResponse
    {
        $year = $request->input('year');
        $commodity_item_id = $request->input('commodity_item_id');

        // Lakukan query untuk mendapatkan data PlanPlant dengan hubungan commodityItem dan subdistrict
        $queryResults = PlanPlant::with(['commodityItem', 'subdistrict'])
            ->whereYear('date', $year)
            ->where('commodity_item_id', $commodity_item_id)
            ->get();
        
        $subdistricts = Kecamatan::all();
        
        $commodity = CommodityItem::find($commodity_item_id);
        

        if($queryResults->count() > 0){
            foreach ($queryResults as $queryResult) {
                // Loop melalui semua subdistrict dari model Kecamatan
                foreach ($subdistricts as $subdistrict) {
                    // Jika subdistrict_id dari hasil query cocok dengan id Kecamatan
                    if ($queryResult['subdistrict_id'] == $subdistrict->id) {
                        // Ambil nama Kecamatan
                        $subdistrictName = $subdistrict->name;
            
                        // Mengonversi tanggal menjadi bulan menggunakan Carbon
                        $month = Carbon::parse($queryResult['date'])->format('M');
                        $value = $queryResult['value'];
            
                        // Set nilai bulan di bawah kunci bulan untuk setiap Kecamatan
                        $result[$subdistrictName][$month] = $value;
                    }
                }
            }
    
    
            
            return response()->json([
                'status' => 'success',
                'message' => 'Product retrieved successfully.',
                'commodity' => $commodity->name,
                'year' => $year,
                'data' => $result
            ]);
        }else{
            return response()->json([
                'status' => 'success',
                'message' => 'Product retrieved successfully.',
                'commodity' => $commodity->name,
                'year' => $year,
                'data' => null
            ]);
        }
                
                // Loop melalui hasil query
        

    }
    
    function timeAgoString($createdAt) {
        $now = Carbon::now();
        $created = Carbon::createFromFormat('Y-m-d H:i:s', $createdAt);
        $diff = $created->diff($now);
    
        if ($diff->y > 0) {
            return $diff->y == 1 ? "1 tahun yang lalu" : $diff->y . " tahun yang lalu";
        } elseif ($diff->m > 0) {
            return $diff->m == 1 ? "1 bulan yang lalu" : $diff->m . " bulan yang lalu";
        } elseif ($diff->d > 0) {
            return $diff->d == 1 ? "1 hari yang lalu" : $diff->d . " hari yang lalu";
        } elseif ($diff->h > 0) {
            return $diff->h == 1 ? "1 jam yang lalu" : $diff->h . " jam yang lalu";
        } elseif ($diff->i > 0) {
            return $diff->i == 1 ? "1 menit yang lalu" : $diff->i . " menit yang lalu";
        } else {
            return "beberapa detik yang lalu";
        }
    }
    
    public function listAdmin(): JsonResponse
    {
        $user = auth()->user();

        $menu = User::where('type', '2')->get();
    
        return $this->sendResponse(CarResource::collection($menu), 'Admin retrieved successfully.');
    }

    public function startChat(Request $request)
    {
        $user = auth()->user();

        $userId = $user->id;
        $adminId = $request->admin_id; // ID admin, sesuaikan dengan kebutuhan Anda

        // Periksa apakah pengguna sudah memiliki chat room dengan admin
        $existingChatRoom = ChatRoom::where('user_id', $userId)
                                    ->where('admin_id', $adminId)
                                    ->first();

        // Jika pengguna belum memiliki chat room dengan admin, buat baru
        if (!$existingChatRoom) {
            $newChatRoom = ChatRoom::create([
                'user_id' => $userId,
                'admin_id' => $adminId
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Chat room created successfully',
                'chat_room_id' => $newChatRoom->id
            ]);
        } else {
            // Jika pengguna sudah memiliki chat room dengan admin sebelumnya
            return response()->json([
                'status' => 'success',
                'message' => 'Chat room already exists',
                'chat_room_id' => $existingChatRoom->id
            ]);
        }
    }

    public function sendMessage(Request $request)
    {
        $user = auth()->user();
        // Validasi input
        $request->validate([
            'chat_room_id' => 'required|exists:chat_room,id',
            'message' => 'required',
            'type' => 'required|in:text,image'
        ]);

        // Membuat pesan baru
        $message = new ChatDetail([
            'chat_room_id' => (int)$request->input('chat_room_id'),
            'sender_id' => $user->id,
            'type' => $request->input('type')
        ]);

        // Menyimpan pesan sesuai jenisnya
        if ($request->input('type') === 'text') {
            // Pesan teks
            $message->message = $request->input('message');
        } elseif ($request->input('type') === 'image') {
            // Pesan gambar
            // Simpan gambar ke dalam direktori atau penyimpanan yang sesuai
            // $request->file('message')->store('path-to-directory');
            // Misalnya, Anda dapat menyimpan path gambar ke dalam kolom 'message'
            $message->message = $request->file('message')->store('chat-images');
        }

        // Menyimpan pesan ke database
        $message->save();

        return response()->json([
            'status' => 'success',
            'data' => $message,
            'message' => 'Message sent successfully'
        ]);
    }

    public function listMessage(Request $request): JsonResponse
    {
        $user = auth()->user();

        $menu = ChatDetail::where('chat_room_id', $request->room_id)->get();
    
        return $this->sendResponse(CarResource::collection($menu), 'Message retrieved successfully.');
    }

    public function listNews(): JsonResponse
    {
        $menu = News::where('type', 'berita')->get();
    
        return $this->sendResponse(CarResource::collection($menu), 'News retrieved successfully.');
    }

    public function listArticle(): JsonResponse
    {
        $menu = News::all();
    
        return $this->sendResponse(CarResource::collection($menu), 'Article retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addCar(Request $request): JsonResponse
    {
        $input = $request->all();

        $user = auth()->user();
   
        $validator = Validator::make($input, [
            'car_name' => 'required',
            'car_brand_id' => 'required',
            'car_model' => 'required',
            'police_number' => 'required',
            'police_number_year' => 'required',
            'tank_type' => 'required',
            'fuel_type_id' => 'required',
            'capacity' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input['user_id'] = $user->id;
        // dd($input);
        $car = Car::create($input);
   
        return $this->sendResponse(new CarResource($car), 'Car created successfully.');
    } 

    public function addEarning(Request $request): JsonResponse
    {
        $input = $request->all();

        $user = auth()->user();
   
        $validator = Validator::make($input, [
            'date' => 'required',
            'time' => 'required',
            'odometer' => 'required',
            'earning_amount' => 'required',
            'earning_type' => 'required',
            'note' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input['user_id'] = $user->id;
        $input['note_type_id'] = 1;
        // dd($input);
        $history = History::create($input);

        if($history->exists){
            $input['history_id'] = $history->id;
            $earning = Earning::create($input);
        }
   
        return $this->sendResponse(null, 'Earning created successfully.');
    }

    public function addCost(Request $request): JsonResponse
    {
        $input = $request->all();

        $user = auth()->user();
   
        $validator = Validator::make($input, [
            'date' => 'required',
            'time' => 'required',
            'odometer' => 'required',
            'cost_type_id' => 'required',
            'location_id' => 'required',
            'payment_method_id' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input['user_id'] = $user->id;
        $input['note_type_id'] = 3;
        // dd($input);
        $history = History::create($input);

        if($history->exists){
            $input['history_id'] = $history->id;
            $charging = Cost::create($input);
        }
   
        return $this->sendResponse(null, 'Cost created successfully.');
    }

    public function addCharging(Request $request): JsonResponse
    {
        $input = $request->all();

        $user = auth()->user();
   
        $validator = Validator::make($input, [
            'date' => 'required',
            'time' => 'required',
            'odometer' => 'required',
            'fuel_id' => 'required',
            'charging_place_id' => 'required',
            'price' => 'required',
            'liter' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input['user_id'] = $user->id;
        $input['note_type_id'] = 2;
        // dd($input);
        $history = History::create($input);

        if($history->exists){
            $input['history_id'] = $history->id;
            $charging = Charging::create($input);
        }
   
        return $this->sendResponse(null, 'Charging created successfully.');
    }

    public function addService(Request $request): JsonResponse
    {
        $input = $request->all();

        $user = auth()->user();
   
        $validator = Validator::make($input, [
            'date' => 'required',
            'time' => 'required',
            'odometer' => 'required',
            'service_type_id' => 'required',
            'location_id' => 'required',
            'payment_method_id' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input['user_id'] = $user->id;
        $input['note_type_id'] = 4;
        // dd($input);
        $history = History::create($input);

        if($history->exists){
            $input['history_id'] = $history->id;
            $service = Service::create($input);
        }
   
        return $this->sendResponse(null, 'Charging created successfully.');
    } 

    public function addRoute(Request $request): JsonResponse
    {
        $input = $request->all();

        $user = auth()->user();
   
        $validator = Validator::make($input, [
            'date' => 'required',
            'time' => 'required',
            'odometer' => 'required',
            'first_location_id' => 'required',
            'last_location_id' => 'required',
            'first_date' => 'required',
            'last_date' => 'required',
            'first_odometer' => 'required',
            'last_odometer' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input['user_id'] = $user->id;
        $input['note_type_id'] = 4;
        // dd($input);
        $history = History::create($input);

        if($history->exists){
            $input['history_id'] = $history->id;
            $input['first_odometer'] = $request->odometer;
            $route = Route::create($input);
        }
   
        return $this->sendResponse(null, 'Route created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showReportCharging(): JsonResponse
    {
        $car = Car::find($id);
  
        if (is_null($car)) {
            return $this->sendError('Car not found.');
        }
   
        return $this->sendResponse(new CarResource($car), 'Car retrieved successfully.');
    }
    
    public function showReportCost(): JsonResponse
    {
        $car = Car::find($id);
  
        if (is_null($car)) {
            return $this->sendError('Car not found.');
        }
   
        return $this->sendResponse(new CarResource($car), 'Car retrieved successfully.');
    }
    
    public function showReportEarning(): JsonResponse
    {
        $car = Car::find($id);
  
        if (is_null($car)) {
            return $this->sendError('Car not found.');
        }
   
        return $this->sendResponse(new CarResource($car), 'Car retrieved successfully.');
    }
    
    public function showReportService(): JsonResponse
    {
        $car = Car::find($id);
  
        if (is_null($car)) {
            return $this->sendError('Car not found.');
        }
   
        return $this->sendResponse(new CarResource($car), 'Car retrieved successfully.');
    }
    
}