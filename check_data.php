<?php

require __DIR__ . '/vendor/autoload.php';

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__)
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);
$app->singleton(
    App\Contracts\DebugExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->boot();

use Illuminate\Support\Facades\DB;

try {
    echo "=== CLASSES ===" . PHP_EOL;
    $classes = DB::table('classes')->get();
    echo "Found " . $classes->count() . " classes" . PHP_EOL;
    foreach ($classes as $class) {
        echo "Class: {$class->title} - Grade: {$class->grade}" . PHP_EOL;
    }

    echo PHP_EOL . "=== CLASS_TEACHERS ===" . PHP_EOL;
    $classTeachers = DB::table('class_teachers')->get();
    echo "Found " . $classTeachers->count() . " class teacher assignments" . PHP_EOL;
    foreach ($classTeachers as $ct) {
        $classTitle = DB::table('classes')->where('id', $ct->class_id)->value('title');
        $teacherName = DB::table('users')->where('id', $ct->teacher_id)->value('first_name');
        echo "Assignment: Class '{$classTitle}' - Teacher ID {$ct->teacher_id} - Subject: {$ct->subject}" . PHP_EOL;
    }

    echo PHP_EOL . "=== CLASS_STUDENTS ===" . PHP_EOL;
    $classStudents = DB::table('class_students')->get();
    echo "Found " . $classStudents->count() . " class student assignments" . PHP_EOL;
    foreach ($classStudents as $cs) {
        $classTitle = DB::table('classes')->where('id', $cs->class_id)->value('title');
        $studentName = DB::table('users')->where('id', $cs->student_id)->value('first_name');
        echo "Assignment: Class '{$classTitle}' - Student ID {$cs->student_id}" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . PHP_EOL;
}
EOF
php check_data.php