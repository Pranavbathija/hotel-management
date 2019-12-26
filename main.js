// const roomsDOM = document.querySelector(".rooms");
const roomsDOM = $(".rooms");
class Rooms {
  async getRooms() {
    try {
      let result = await fetch("rooms.json");
      let data = await result.json();
      let rooms = data.roomTypes;

      return rooms;
    } catch (error) {
      console.log(error);
    }
  }
}

class UI {
  displayRooms(rooms) {
    let result = "";

    rooms.forEach(room => {
      result += `
            <article class="room">
            <h3 class="heading">${room.type}</h3>
              <div class="img-container">
                  <img class="room-img" src=${room.image} alt="Room" />
                  <a href="accommodation.html" class="room-btn" data-id=${room.id}>
                   Explore
                   <i class="fas fa-long-arrow-alt-right"></i>
                  </a>
              </div>
            </article>
            `;
    });

    // roomsDOM.innerHTML = result;
    roomsDOM.html(result);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const ui = new UI();
  const rooms = new Rooms();
  rooms.getRooms().then(rooms => {
    ui.displayRooms(rooms);
  });
});
