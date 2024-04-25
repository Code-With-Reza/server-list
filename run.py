import requests
import json
from tqdm import tqdm

def get_server_info(ip):
    url = f"https://api.mcsrvstat.us/bedrock/3/{ip}"
    response = requests.get(url)
    if response.status_code == 200:
        server_data = response.json()
        if "ip" in server_data:
            ip = server_data["ip"]
            if server_data["online"]:  # Check if server is online
                if "players" in server_data and "online" in server_data["players"] and "max" in server_data["players"]:
                    players_online = server_data["players"]["online"]
                    players_max = server_data["players"]["max"]
                else:
                    players_online = 0
                    players_max = 0
                world = server_data.get("map", {}).get("clean", "Unknown")
                gamemode = server_data.get("gamemode", "Unknown")
                version = server_data.get("version", "Unknown")
                return f"{ip}_{players_online},{players_max}_{world}_{gamemode}_{version}"
            else:
                return f"Server {ip} is offline"
        else:
            return "Error: Invalid response format"
    else:
        return f"Error: API request failed with status code {response.status_code}"

# Read IPs from list.txt
with open("list.txt", "r") as file:
    lines = file.readlines()

# Fetch data from API and save to server.txt with progress bar
with open("server.txt", "w") as file:
    for line in tqdm(lines, desc="Fetching Servers", unit=" IP"):
        ip = line.split()[0]
        server_info = get_server_info(ip)
        if server_info.startswith("Server"):
            tqdm.write(server_info)  # Display offline server message
        else:
            file.write(server_info + "\n")
