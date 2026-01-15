<?php

namespace App\Http\Controllers\Api; // 1. اتأكد إن الـ Namespace فيه Api

use App\Http\Controllers\Controller; // 2. لازم نستدعي الكنترولر الأساسي
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
  // دالة إضافة مشروع جديد
  public function store(Request $request)
  {
    // نأكد إن اللي بيضيف مشروع هو Client فقط
    if (!$request->user()->hasRole('client')) {
      return response()->json(['message' => 'Unauthorized. Only clients can post projects.'], 403);
    }

    $validated = $request->validate([
      'title'       => 'required|string|max:255',
      'description' => 'required|string',
      'budget'      => 'required|numeric|min:5',
    ]);

    $project = $request->user()->projects()->create($validated);

    return response()->json([
      'message' => 'Project created successfully!',
      'project' => $project
    ], 201);
  }

  // دالة عرض كل المشاريع
  public function index()
  {
    return Project::with('owner')->latest()->get();
  }
  // جلب مشاريع المستخدم اللي عامل Login حالياً فقط
  public function myProjects(Request $request)
  {
    $projects = $request->user()->projects()->latest()->get();
    return response()->json($projects);
  }
  // دالة عرض تفاصيل مشروع واحد
  public function show($id)
  {
      // تأكد إنك بتعمل return للـ json صح
      $project = Project::with('owner')->find($id);
      
      if (!$project) {
          return response()->json(['message' => 'Project not found'], 404);
      }
  
      return response()->json($project); // لارافيل هيحول الموديل لـ JSON تلقائياً
  }
  public function update(Request $request, $id)
  {
    $project = Project::findOrFail($id);

    // حماية: التأكد إن اللي بيعدل هو صاحب المشروع نفسه
    if ($request->user()->id !== $project->user_id) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
      'title'       => 'required|string|max:255',
      'description' => 'required|string',
      'budget'      => 'required|numeric|min:5',
    ]);

    $project->update($validated);

    return response()->json(['message' => 'Project updated!', 'project' => $project]);
  }
  public function destroy(Request $request, $id)
  {
    try {
      // 1. البحث عن المشروع
      $project = Project::findOrFail($id);

      // 2. التحقق من الصلاحية (صاحب المشروع هو اللي بيمسح)
      if ($request->user()->id !== $project->user_id) {
        return response()->json(['message' => 'Unauthorized'], 403);
      }

      // 3. المسح الفعلي
      $project->delete();

      return response()->json(['message' => 'Deleted Successfully']);
    } catch (\Exception $e) {
      // لو حصل أي خطأ، ابعت رسالة توضح السبب
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }
  // دالة لجلب كل المشاريع لكل اليوزرز
public function getAllProjects()
{
    // بنجيب المشاريع مع بيانات صاحبها (owner) ومرتبة من الأحدث
    $projects = Project::with('owner')->latest()->get();
    return response()->json($projects);
}
}
