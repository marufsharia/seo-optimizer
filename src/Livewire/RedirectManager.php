<?php

namespace HyroPlugins\SeoOptimizer\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class RedirectManager extends Component
{
    use WithPagination;
    
    public $showModal = false;
    public $editingId = null;
    public $old_url = '';
    public $new_url = '';
    public $status_code = 301;
    public $is_active = true;
    public $search = '';
    
    protected $rules = [
        'old_url' => 'required|string|max:500',
        'new_url' => 'required|string|max:500',
        'status_code' => 'required|in:301,302',
        'is_active' => 'boolean',
    ];
    
    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }
    
    public function edit($id)
    {
        $redirect = DB::table('seo_redirects')->where('id', $id)->first();
        
        if (!$redirect) {
            session()->flash('error', 'Redirect not found!');
            return;
        }
        
        $this->editingId = $redirect->id;
        $this->old_url = $redirect->old_url;
        $this->new_url = $redirect->new_url;
        $this->status_code = $redirect->status_code;
        $this->is_active = $redirect->is_active;
        
        $this->showModal = true;
    }
    
    public function save()
    {
        $this->validate();
        
        if ($this->editingId) {
            DB::table('seo_redirects')
                ->where('id', $this->editingId)
                ->update([
                    'old_url' => $this->old_url,
                    'new_url' => $this->new_url,
                    'status_code' => $this->status_code,
                    'is_active' => $this->is_active,
                    'updated_at' => now(),
                ]);
            
            session()->flash('success', 'Redirect updated successfully!');
        } else {
            DB::table('seo_redirects')->insert([
                'old_url' => $this->old_url,
                'new_url' => $this->new_url,
                'status_code' => $this->status_code,
                'is_active' => $this->is_active,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            session()->flash('success', 'Redirect created successfully!');
        }
        
        $this->closeModal();
    }
    
    public function delete($id)
    {
        DB::table('seo_redirects')->where('id', $id)->delete();
        session()->flash('success', 'Redirect deleted successfully!');
    }
    
    public function toggleActive($id)
    {
        $redirect = DB::table('seo_redirects')->where('id', $id)->first();
        
        if ($redirect) {
            DB::table('seo_redirects')
                ->where('id', $id)
                ->update([
                    'is_active' => !$redirect->is_active,
                    'updated_at' => now(),
                ]);
            
            session()->flash('success', 'Redirect status updated!');
        }
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }
    
    protected function resetForm()
    {
        $this->editingId = null;
        $this->old_url = '';
        $this->new_url = '';
        $this->status_code = 301;
        $this->is_active = true;
        $this->resetErrorBag();
    }
    
    public function render()
    {
        $redirectsQuery = DB::table('seo_redirects');
        
        if ($this->search) {
            $redirectsQuery->where(function($query) {
                $query->where('old_url', 'like', '%' . $this->search . '%')
                    ->orWhere('new_url', 'like', '%' . $this->search . '%');
            });
        }
        
        $redirects = $redirectsQuery
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('hyro-plugin-seo-optimizer::livewire.redirect-manager', [
            'redirects' => $redirects,
        ]);
    }
}
