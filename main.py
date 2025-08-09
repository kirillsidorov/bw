import requests
import csv
import time
import json

# НАСТРОЙКИ
API_KEY = 'AIzaSyDM3-uWvpdQEvScktLVm_yf85sgO0zbkAE'  # Замени на свой API ключ
SEARCH_REGION = 'Barcelona, Spain'
OUTPUT_FILE = 'wineries_barcelona.csv'
REGION_NAME = 'Barcelona'

# Ключевые слова для поиска виноделен
WINERY_KEYWORDS = [
    'winery', 'vineyard', 'bodega', 'wine cellar', 'wine tasting',
    'viñedo', 'celler', 'caves', 'wine estate', 'viticulture'
]

def get_place_details(place_id):
    """Получить детали о месте по place_id с правильными полями"""
    url = 'https://maps.googleapis.com/maps/api/place/details/json'
    
    # Используем только проверенные поля
    valid_fields = [
        'place_id', 'name', 'formatted_address', 'geometry', 'rating', 
        'user_ratings_total', 'price_level', 'types', 'website', 
        'formatted_phone_number', 'opening_hours', 'business_status', 
        'reviews', 'photos', 'url'
    ]
    
    params = {
        'place_id': place_id,
        'fields': ','.join(valid_fields),
        'key': API_KEY,
        'language': 'en'
    }
    
    print(f"Получаем детали для place_id: {place_id}")
    
    try:
        response = requests.get(url, params=params)
        
        if response.status_code != 200:
            print(f"HTTP ошибка {response.status_code} для {place_id}")
            return None
            
        data = response.json()
        
        if data.get('status') == 'OK':
            result = data.get('result', {})
            print(f"✓ Получены детали для: {result.get('name', 'Unknown')}")
            
            # Обрабатываем opening_hours
            opening_hours = result.get('opening_hours', {})
            weekday_text = ''
            if opening_hours and 'weekday_text' in opening_hours:
                weekday_text = ' | '.join(opening_hours['weekday_text'])
            
            # Обрабатываем reviews  
            reviews_text = ''
            if result.get('reviews'):
                reviews_list = []
                for review in result.get('reviews', [])[:3]:
                    author = review.get('author_name', 'Anonymous')
                    rating = review.get('rating', '')
                    text = review.get('text', '')[:200]
                    if text:
                        reviews_list.append(f"{author} ({rating}★): {text}")
                reviews_text = ' || '.join(reviews_list)
            
            # Обрабатываем geometry
            geometry = result.get('geometry', {})
            location = geometry.get('location', {})
            lat = location.get('lat', '')
            lng = location.get('lng', '')
            
            # Определяем провинцию из адреса
            address = result.get('formatted_address', '')
            province = extract_province_from_address(address)
            
            return {
                'place_id': result.get('place_id', ''),
                'name': result.get('name', ''),
                'formatted_address': address,
                'province': province,
                'latitude': lat,
                'longitude': lng,
                'rating': result.get('rating', ''),
                'user_ratings_total': result.get('user_ratings_total', ''),
                'price_level': result.get('price_level', ''),
                'types': ', '.join(result.get('types', [])),
                'website': result.get('website', ''),
                'formatted_phone_number': result.get('formatted_phone_number', ''),
                'business_status': result.get('business_status', ''),
                'opening_hours_weekday_text': weekday_text,
                'opening_hours_open_now': opening_hours.get('open_now', ''),
                'reviews': reviews_text,
                'url': result.get('url', '')
            }
        else:
            print(f"✗ API ошибка для {place_id}: {data.get('status')} - {data.get('error_message', '')}")
            return None
            
    except Exception as e:
        print(f"✗ Исключение для {place_id}: {e}")
        return None

def extract_province_from_address(address):
    """Извлекает провинцию из адреса для Испании"""
    if not address:
        return ''
    
    # Список провинций Испании и Каталонии
    provinces = {
        'Barcelona': 'Barcelona',
        'Girona': 'Girona', 
        'Lleida': 'Lleida',
        'Tarragona': 'Tarragona',
        'Catalunya': 'Catalunya',
        'Cataluña': 'Catalunya',
        'Spain': 'Spain'
    }
    
    for province_name in provinces.keys():
        if province_name in address:
            return provinces[province_name]
    
    return ''

def get_places_by_keyword(keyword):
    """Получить места по конкретному ключевому слову"""
    places = []
    url = 'https://maps.googleapis.com/maps/api/place/textsearch/json'
    
    params = {
        'query': f"{keyword} near {SEARCH_REGION}",
        'key': API_KEY,
        'type': 'establishment'
    }
    
    print(f"Ищем по ключевому слову: '{keyword}' в регионе: {SEARCH_REGION}")
    
    while True:
        try:
            response = requests.get(url, params=params)
            data = response.json()
            
            if data.get('status') != 'OK':
                print(f"Ошибка API для '{keyword}': {data.get('status')} - {data.get('error_message', 'Unknown error')}")
                break
                
            current_results = data.get('results', [])
            places.extend(current_results)
            print(f"Найдено {len(current_results)} результатов для '{keyword}' (всего: {len(places)})")

            next_page = data.get('next_page_token')
            if not next_page:
                break
                
            # Пауза перед следующим запросом
            time.sleep(3)
            params = {
                'pagetoken': next_page,
                'key': API_KEY
            }
                
        except Exception as e:
            print(f"Ошибка при поиске по '{keyword}': {e}")
            break
    
    return places

def get_all_places():
    """Получить все места по всем ключевым словам винодельни"""
    all_places = []
    place_ids_seen = set()
    
    for keyword in WINERY_KEYWORDS:
        places = get_places_by_keyword(keyword)
        
        # Убираем дубликаты по place_id
        for place in places:
            place_id = place.get('place_id')
            if place_id and place_id not in place_ids_seen:
                place_ids_seen.add(place_id)
                place['search_keyword'] = keyword
                all_places.append(place)
        
        # Пауза между разными ключевыми словами
        time.sleep(2)
    
    print(f"Всего уникальных мест найдено: {len(all_places)}")
    return all_places

def save_to_csv(places):
    """Сохранить все данные в CSV"""
    fieldnames = [
        'Search Keyword', 'Region', 'Province', 'Place ID', 'Name', 'Address',
        'Latitude', 'Longitude', 'Rating', 'User Ratings Total', 'Price Level',
        'Types', 'Website', 'Formatted Phone Number', 'Business Status', 
        'Opening Hours Weekday Text', 'Opening Hours Open Now', 'Reviews', 'Google URL'
    ]
    
    successful_count = 0
    failed_count = 0
    
    with open(OUTPUT_FILE, 'w', newline='', encoding='utf-8') as csvfile:
        writer = csv.DictWriter(csvfile, fieldnames=fieldnames)
        writer.writeheader()
        
        for i, place in enumerate(places):
            place_name = place.get('name', 'Unknown')
            print(f"\nОбработка винодельни {i+1}/{len(places)}: {place_name}")
            
            # Получаем детальную информацию
            details = get_place_details(place.get('place_id'))
            
            if details:
                row_data = {
                    'Search Keyword': place.get('search_keyword', ''),
                    'Region': REGION_NAME,
                    'Province': details.get('province', ''),
                    'Place ID': details.get('place_id', ''),
                    'Name': details.get('name', ''),
                    'Address': details.get('formatted_address', ''),
                    'Latitude': details.get('latitude', ''),
                    'Longitude': details.get('longitude', ''),
                    'Rating': details.get('rating', ''),
                    'User Ratings Total': details.get('user_ratings_total', ''),
                    'Price Level': details.get('price_level', ''),
                    'Types': details.get('types', ''),
                    'Website': details.get('website', ''),
                    'Formatted Phone Number': details.get('formatted_phone_number', ''),
                    'Business Status': details.get('business_status', ''),
                    'Opening Hours Weekday Text': details.get('opening_hours_weekday_text', ''),
                    'Opening Hours Open Now': details.get('opening_hours_open_now', ''),
                    'Reviews': details.get('reviews', ''),
                    'Google URL': details.get('url', '')
                }
                
                writer.writerow(row_data)
                successful_count += 1
                print(f"✓ Записана: {details.get('name')}")
            else:
                # Записываем базовую информацию если детали не получены
                print(f"✗ Не удалось получить детали, записываем базовую информацию")
                row_data = {
                    'Search Keyword': place.get('search_keyword', ''),
                    'Region': REGION_NAME,
                    'Province': '',
                    'Place ID': place.get('place_id', ''),
                    'Name': place.get('name', ''),
                    'Address': place.get('formatted_address', ''),
                    'Latitude': place.get('geometry', {}).get('location', {}).get('lat', ''),
                    'Longitude': place.get('geometry', {}).get('location', {}).get('lng', ''),
                    'Rating': place.get('rating', ''),
                    'User Ratings Total': place.get('user_ratings_total', ''),
                    'Price Level': place.get('price_level', ''),
                    'Types': ', '.join(place.get('types', [])),
                    'Website': '',
                    'Formatted Phone Number': '',
                    'Business Status': place.get('business_status', ''),
                    'Opening Hours Weekday Text': '',
                    'Opening Hours Open Now': '',
                    'Reviews': '',
                    'Google URL': ''
                }
                writer.writerow(row_data)
                failed_count += 1
            
            # Пауза между запросами деталей
            time.sleep(1)
    
    print(f"\n=== ИТОГИ ===")
    print(f"Успешно обработано: {successful_count}")
    print(f"Ошибки получения деталей: {failed_count}")
    print(f"Общий итог: {successful_count + failed_count}")

def get_nearby_places_radius(center_lat, center_lng, radius_km=50):
    """Дополнительный поиск в радиусе от центра Барселоны"""
    places = []
    url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json'
    
    # Конвертируем км в метры
    radius_m = radius_km * 1000
    
    params = {
        'location': f"{center_lat},{center_lng}",
        'radius': radius_m,
        'keyword': 'winery vineyard bodega wine',
        'key': API_KEY,
        'type': 'establishment'
    }
    
    print(f"Поиск в радиусе {radius_km}км от центра Барселоны...")
    
    try:
        response = requests.get(url, params=params)
        data = response.json()
        
        if data.get('status') == 'OK':
            places = data.get('results', [])
            print(f"Найдено {len(places)} мест в радиусе {radius_km}км")
        else:
            print(f"Ошибка поиска в радиусе: {data.get('status')}")
            
    except Exception as e:
        print(f"Ошибка при поиске в радиусе: {e}")
    
    return places

if __name__ == '__main__':
    print(f"Начинаем поиск виноделен в регионе: {REGION_NAME}")
    print(f"Ключевые слова для поиска: {', '.join(WINERY_KEYWORDS)}")
    
    # Основной поиск по ключевым словам
    places = get_all_places()
    
    # Дополнительный поиск в радиусе от центра Барселоны
    barcelona_center_lat = 41.3851
    barcelona_center_lng = 2.1734
    nearby_places = get_nearby_places_radius(barcelona_center_lat, barcelona_center_lng, 100)
    
    # Объединяем результаты и убираем дубликаты
    place_ids_seen = {place.get('place_id') for place in places}
    for place in nearby_places:
        place_id = place.get('place_id')
        if place_id and place_id not in place_ids_seen:
            place['search_keyword'] = 'nearby_search'
            places.append(place)
            place_ids_seen.add(place_id)
    
    if places:
        print(f"\nВсего найдено {len(places)} уникальных виноделен. Получаем детальную информацию...")
        save_to_csv(places)
        print(f'\nГотово! Данные сохранены в {OUTPUT_FILE}')
    else:
        print("Виноделен не найдено.")