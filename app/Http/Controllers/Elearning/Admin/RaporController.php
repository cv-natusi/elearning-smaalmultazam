<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RaporController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data['title'] = 'E-RAPOR';
    }

    /**
     * 🔹 Halaman utama menampilkan iframe
     */
    public function main()
    {
        $data = $this->data;

        // Ambil dari .env
        $iframeUrl = env('IFRAME_RAPOR_URL', '');

        $data['iframe_url'] = $iframeUrl;

        return view('main.content.admin.rapor.main', $data);
    }

    /**
     * 🔹 Halaman pengaturan iframe
     */
    public function iframeSetting()
    {
        $data = $this->data;
        $data['iframe_url'] = env('IFRAME_RAPOR_URL', '');
        return view('main.content.admin.rapor.form', $data);
    }

    public function iframeSettingSave(Request $request)
    {
        $rules = ['iframe_url' => 'required|url'];
        $messages = ['iframe_url.required' => 'Link iframe wajib diisi.', 'iframe_url.url' => 'Format link tidak valid.'];
        $validate = Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        $this->setEnvironmentValue('IFRAME_RAPOR_URL', $request->iframe_url);

        return redirect()->route('rapor.main')->with('success', 'Link iframe berhasil diperbarui.');
    }
    /**
     * 🔹 Update / tambah key di file .env
     */
    private function setEnvironmentValue($key, $value)
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            return false;
        }

        $env = file_get_contents($path);
        $pattern = "/^{$key}=.*/m";
        $value = trim($value);

        if (preg_match($pattern, $env)) {
            $env = preg_replace($pattern, "{$key}=\"{$value}\"", $env);
        } else {
            $env .= "\n{$key}=\"{$value}\"";
        }

        file_put_contents($path, $env);
        return true;
    }
}
