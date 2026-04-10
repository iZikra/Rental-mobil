import time
import requests
import concurrent.futures

# Configuration
BASE_URL = "http://localhost:8000"  # Update with your local server URL
ENDPOINTS = [
    "/",
    "/katalog",
    "/about",
    "/contact",
]
CONCURRENT_USERS = 100
TOTAL_REQUESTS = 100

def measure_response_time(url):
    try:
        start_time = time.time()
        response = requests.get(url, timeout=10)
        end_time = time.time()
        
        duration = end_time - start_time
        return {
            "url": url,
            "status_code": response.status_code,
            "duration": duration,
            "success": response.status_code == 200
        }
    except Exception as e:
        return {
            "url": url,
            "status_code": None,
            "duration": None,
            "success": False,
            "error": str(e)
        }

def run_performance_test():
    print(f"Warming up server...")
    try:
        for ep in ENDPOINTS:
            requests.get(BASE_URL + ep, timeout=5)
    except: pass

    print(f"Starting Performance Test with {CONCURRENT_USERS} concurrent requests...")
    
    results = []
    with concurrent.futures.ThreadPoolExecutor(max_workers=CONCURRENT_USERS) as executor:
        # Create 100 tasks randomly distributed across endpoints
        tasks = []
        import random
        for _ in range(TOTAL_REQUESTS):
            endpoint = random.choice(ENDPOINTS)
            tasks.append(executor.submit(measure_response_time, BASE_URL + endpoint))
        
        for future in concurrent.futures.as_completed(tasks):
            results.append(future.result())
            
    # Analyze results
    success_count = sum(1 for r in results if r["success"])
    durations = [r["duration"] for r in results if r["success"]]
    
    if success_count < len(results):
        print("\nErrors encountered:")
        errors = set(r.get("error") for r in results if not r["success"])
        for err in errors:
            print(f"- {err}")

    avg_duration = sum(durations) / len(durations) if durations else 999
    max_duration = max(durations) if durations else 0
    min_duration = min(durations) if durations else 0
    
    print("\n--- Performance Test Results ---")
    print(f"Total Requests: {len(results)}")
    print(f"Successful Requests: {success_count}")
    print(f"Failed Requests: {len(results) - success_count}")
    print(f"Average Response Time: {avg_duration:.4f}s")
    print(f"Max Response Time: {max_duration:.4f}s")
    print(f"Min Response Time: {min_duration:.4f}s")
    
    if avg_duration < 2.0:
        print("RESULT: PASS (Average response time < 2s)")
    else:
        print("RESULT: FAIL (Average response time >= 2s)")

if __name__ == "__main__":
    # Check if server is running before starting the test
    try:
        requests.get(BASE_URL, timeout=2)
        run_performance_test()
    except requests.exceptions.ConnectionError:
        print(f"Error: Could not connect to {BASE_URL}. Make sure your Laravel server is running.")
