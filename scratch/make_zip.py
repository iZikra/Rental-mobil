import os
import zipfile

def zipdir(path, ziph, root_path):
    if not os.path.exists(path):
        return
    for root, dirs, files in os.walk(path):
        for file in files:
            file_path = os.path.join(root, file)
            ziph.write(file_path, os.path.relpath(file_path, root_path))

with zipfile.ZipFile('update_frontend_final.zip', 'w', zipfile.ZIP_DEFLATED) as zipf:
    zipdir('app', zipf, '.')
    zipdir('resources/views', zipf, '.')
    zipdir('public/build', zipf, '.')
    zipdir('storage/app/public', zipf, '.') # Include images!
    zipf.write('routes/web.php', 'routes/web.php')
    zipf.write('clear_cache.php', 'clear_cache.php')
    zipf.write('deploy_frontend.php', 'deploy_frontend.php')

print("Zip created successfully with images.")
