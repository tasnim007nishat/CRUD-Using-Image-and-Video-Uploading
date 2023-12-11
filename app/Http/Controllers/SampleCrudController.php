<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sample_Crud;
use Illuminate\Support\Facades\Storage;

class SampleCrudController extends Controller
{
    public function index() {
        return view('index');
    }

    public function fetchAll() {
        $sample = Sample_Crud::all();
        $output = '';
        if ($sample->count()>0){
            $output .='<table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Video</th>
                    <th>Action</th>
                </tr>
            </thead>
            </tbody>';
            foreach($sample as $sample){
                $output .= '<tr>
                <td>' . $sample->id . '</td>
                <td><img src="storage/SampleImages/' . $sample->images . '"width="100" class="img-thumbnail rounded" ></td>
                <td><video width="100"><source src="storage/SampleVideos/' . $sample->videos . '" type="video/mp4"></video></td>
                <td>
                    <a href="#" id="' . $sample . '"class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editUploadModal"><i class="bi-pencil-square h4"></i></a>
                    <a href="#" id="' . $sample->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
                </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class = "text-center text-secondary my-5">No records found</h1>';
        }
    }

    //adding images ajax request
    public function store(Request $request) {
        //image
        $file_image = $request->file('image');
        $fileName_image = time() . '.' . $file_image->getClientOriginalExtension();
        $file_image->storeAs('public/SampleImages', $fileName_image);

        //video
        $file_video = $request->file('video');
        $fileName_video = time() . '.' . $file_video->getClientOriginalExtension();
        $file_video->storeAs('public/SampleVideos', $fileName_video);

        $sampleData = ['images' => $fileName_image, 'videos' => $fileName_video];
        Sample_Crud::create($sampleData);
        return response() -> json([
            'status' => 200,
        ]);
    }

    //edit images ajax request
    public function edit(Request $request) {
        $id = $request->id;
        $sample = Sample_Crud::find($id);
        return response() -> json($sample);
        //return view('index');
        // dd($id);
    }

    //updating images ajax request
    // public function update(Request $request) {
    //     $fileName_image = '';
    //     $fileName_video = '';
    //     $upload = Sample_Crud::find($request->upload_id);
    //     if($request->hasFile('image') || $request->hasFile('video')) {
    //         $file_image = $request->file('image');
    //         $fileName_image = time() . '.' . $file_image->getClientOriginalExtension();
    //         $file_image->storeAs('storage/SampleImages', $fileName_image);

    //         $file_video = $request->file('video');
    //         $fileName_video = time() . '.' . $file_video->getClientOriginalExtension();
    //         $file_video->storeAs('storage/SampleVideos', $fileName_video);

    //         if($upload->image || $upload->video) {
    //             Storage::delete('storage/SampleImages' || 'storage/SampleVideos', $upload->image || $upload->video);
    //         } else {
    //             $fileName_image = $request->image;
    //             $fileName_video = $request->video;
    //         }

    //         $sampleData = ['images' => $request->image, 'videos' => $request->video];

    //         $upload->update($sampleData);
    //         return response() -> json([
    //             'status' => 200,
    //         ]);
    //     }
    // }

    public function update(Request $request) {
		$fileName_image = '';
		$fileName_video = '';
        $upload = Sample_Crud::find($request->upload_id);
		if ($request->hasFile('image')) {
			$file_image = $request->file('image');
			$fileName_image = time() . '.' . $file_image->getClientOriginalExtension();
			$file_image->storeAs('public/storage/SampleImages', $fileName_image);
			if ($upload->image) {
				Storage::delete('public/storage/SampleImages/' . $upload->image);
			}
		// } else {
			$fileName_image = $request->image;
		}

        if ($request->hasFile('video')) {
			$file_video = $request->file('video');
			$fileName_video = time() . '.' . $file_video->getClientOriginalExtension();
			$file_video->storeAs('public/storage/SampleVideos', $fileName_video);
			if ($upload->video) {
				Storage::delete('public/storage/SampleVideos/' . $upload->video);
			}
		// } else {
			$fileName_video = $request->video;
		}

		$uploadData = ['image' => $fileName_image, 'video' => $fileName_video];

		$upload->update($uploadData);
		return response()->json([
			'status' => 200,
		]);
	}

    // delete an employee ajax request
    public function delete(Request $request) {
        $id = $request->id;
        $sample = Sample_Crud::find($id);
        if (Storage::delete('public/SampleImages/' || 'public/SampleVideos/' . $sample->image || $sample->video)) {
            Sample_Crud::destroy($id);
        }
    }
}

