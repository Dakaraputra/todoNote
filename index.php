<?php
require 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en" class="font-poppins">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo and Notes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
        extend: {
            fontFamily: {
            poppins: ['Poppins', 'sans-serif'],
            },
            colors: {
                    primary: '#4F46E5', // Indigo 
                    secondary: '#64748B', // Slate
                }
        }
        }
    }
    </script>

</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased">
    <?php
    $sqlTodos = "SELECT * FROM todos ORDER BY id DESC";
    $resultTodos = pg_query($conn, $sqlTodos);

    $todos = $resultTodos ? pg_fetch_all($resultTodos) : [];

    $sqlNotes = "SELECT * FROM notes ORDER BY id DESC";
    $resultNotes = pg_query($conn, $sqlNotes);

    $notes = $resultNotes ? pg_fetch_all($resultNotes) : [];
    ?>
    <header bg-white shadow-sm sticky top-0>
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <H2 class="text-2xl font-bold text-primary">Todo and Note by Dakara</H2>
        <nav>
            <ul class="flex space-x-6 text-sm font-medium text-gray-600">
                <li><a href="#todo-section" class="hover:text-primary transition-colors">Todo</a></li>
                <li><a href="#note-section" class="hover:text-primary transition-colors">Notes</a></li>
            </ul>
        </nav>
        </div>
    </header>
    <section class="py-10">
        <div class="container mx-auto px-4 max-w-3xl">
            <h2 class="text-2xl font-bold mb-6 text-slate-700 border-b pb-2 border-gray-200">Todo list</h2>
            <div class="bg-white p-6 rounded-xl shadow-md mb-8 border border-gray-100">
                <form action="./Handlers/uploadTodo.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                    <input type="area" name="task" placeholder="Apa yang ingin dikerjakan?" required
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    <input type="file" name="todo_image" accept="image/*"
                        class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-primary hover:file:bg-indigo-100">
                    <button type="submit" name="todo_submit"
                        class="bg-primary text-white font-semibold py-2 px-6 rounded-lg hover:bg-indigo-700 transition duration-200 shadow-sm w-full md:w-auto self-end">
                        Tambah
                    </button>
                </form>
            </div >
            <div class="space-y-4">
                <?php foreach ($todos as $todo):?>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row items-center gap-4 transition hover:shadow-md">
                    <div class="flex-1 flex items-center gap-4 w-full">
                        <?php if ($todo['image_path']):?>
                        <img src="uploads/<?php echo htmlspecialchars(basename($todo['image_path'])); ?>" alt="Todo Image" 
                            class="w-32 h-32 object-cover rounded-lg shadow-sm border border-gray-100 flex-shrink-0">
                        <?php endif; ?>
                        <div class="relative flex items-center">
                            <input type="checkbox"<?php echo $todo['is_complete'] ? 'checked' : ''; ?>
                            class="w-6 h-6 text-primary border-gray-300 rounded focus:ring-primary cursor-pointer accent-indigo-600 ">
                        </div>
                            <span class="text-gray-700 font-medium mx-4">
                                <?php echo htmlspecialchars($todo['task']); ?>
                            </span>
                    </div>
                        <div class="flex items-center gap-2 mt-2 sm:mt-0 w-full sm:w-auto justify-end">
                            
                            <a href="./Handlers/updateTodo.php?id=<?php echo $todo['id']; ?>"
                                class="text-yellow-500 font-medium border-gray-200 bg-yellow-100 py-1.5 px-3 rounded-md hover:bg-yellow-500 transition hover:text-white">
                            <button>Edit</button>
                            </a>

                            <form action="./Handlers/deleteTodo.php" method="POST" style="display:inline;" class="text-red-700 font-medium bg-red-100 py-1.5 px-3 rounded-md hover:bg-red-500 transition hover:text-white">
                                <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                                <button type="submit">Delete</button>
                            </form>
                            
                        </div>   
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<div class="container mx-auto px-4"><hr class="border-gray-200"></div>

    <section id="note-section" class="py-10 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6 text-slate-700 border-b pb-2 border-gray-200">Notes</h2>
            <div class="bg-white p-6 rounded-xl shadow-md mb-10 border border-gray-100 max-w-3xl mx-auto">
                <form action="./Handlers/uploadNote.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                    <input type="text" name="title" placeholder="Catatan Hari ini" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary font-semibold">
                    
                    <textarea name="content" placeholder="Tulis isi catatan di sini..." rows="3" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary resize-y"></textarea>
                    <div class="flex justify-between items-center">    
                        <input type="file" name="notes_image" accept="image/*"
                            class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-primary hover:file:bg-indigo-100">
                        <button type="submit" name="notes_submit"
                            class="bg-primary text-white font-semibold py-2 px-8 rounded-lg hover:bg-indigo-700  shadow-sm">
                        Tambah</button>
                    </div>
                </form>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($notes as $note):?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300 flex flex-col h-full">
                    <h3 class="font-bold text-lg text-gray-700 mb-2 mx-4 my-2"><?php echo htmlspecialchars($note['title']);?></h3>
                    <?php if ($note['image_path']):?>
                        <div class="h-48 overflow-hidden bg-gray-100">
                            <img src="uploads/<?php echo htmlspecialchars(basename($note['image_path'])); ?>" alt="Todo Image"
                            class="w-full h-full object-cover transition duration-500 hover:scale-105">
                        </div>
                    <?php endif; ?>
                    <div class="p-5 flex-1 flex flex-col">
                        <p class="text-gray-600 text-sm mb-4 flex-1 whitespace-pre-line leading-relaxed"><?php echo nl2br(htmlspecialchars($note['contents']));?></p>
                        <div class="flex justify-between items-center mt-auto pt-4 border-t border-gray-100">
                            <small><?php echo date('d M Y', strtotime($note['createdAt']));?></small>
                            <div class="flex gap-2">
                                <a href="./Handlers/updateNote.php?id=<?php echo $note['id']; ?>" class="text-xs bg-yellow-100 text-yellow-500 py-1 px-3 rounded hover:bg-yellow-500 hover:text-white transition"> 
                                Edit</a>
                                <form action="./Handlers/deleteNote.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                                    <button type="submit" class="text-xs bg-red-50 text-red-600 py-1 px-3 rounded hover:bg-red-500 hover:text-white transition">
                                        Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer class="bg-white py-6 mt-12 border-t border-gray-200 text-center text-gray-400 text-sm">
        &copy; <?php echo date('M Y'); ?> Todo & Note App by Dakara.
    </footer>
</body>
</html>

