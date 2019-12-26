// const roomDOM = document.querySelector(".rooms-container");
const roomsDOM = $(".rooms-container");

class Rooms {
  async getRooms() {
    let result = await fetch("rooms.json");
    let data = await result.json();
    let rooms = data.roomTypes;
    return rooms;
  }
}
class UI {
  displayRooms(rooms) {
    let result = "";
    rooms.forEach(room => {
      result += `

        <div class="room-desc">
          <img class="acc-room-img" src="${room.image}" alt="" />
          <div class="acc-room-desc">
            <h3 class="heading">${room.type}</h3>
            <span>
              ${room.descprition}
            </span>
          </div>
          <br />
        </div>
         <hr>
      `;
      // roomDOM.innerHTML = result;
      roomsDOM.html(result);
    });
  }
}
document.addEventListener("DOMContentLoaded", () => {
  let ui = new UI();
  const rooms = new Rooms();
  rooms.getRooms().then(rooms => {
    ui.displayRooms(rooms);
  });
});
